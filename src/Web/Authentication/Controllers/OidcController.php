<?php
/**
 * @copyright 2019-2022 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication\Controllers;

use Aura\Di\Container;
use Jumbojett\OpenIDConnectClient;

use Web\Controller;
use Web\Template;
use Web\View;

class OidcController extends Controller
{
    private $return_url;
    private $auth;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->auth = $this->di->get('Web\Authentication\AuthenticationService');
    }

    /**
     * Try to do OpenID Connect authentication
     */
    public function __invoke(array $params): View
    {
        $_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;

        // If they don't have OpenID configured, send them onto the application's
        // internal authentication system
        global $AUTHENTICATION;
        if (empty($AUTHENTICATION['oidc']['client_id'])) {
            return new \Web\Views\NotFoundView();
        }

        $config = $AUTHENTICATION['oidc'];
        $oidc   = new OpenIDConnectClient($config['server'], $config['client_id'], $config['client_secret']);
        $oidc->addScope(['openid', 'allatclaims', 'profile']);
        $oidc->setAllowImplicitFlow(true);
        $success = $oidc->authenticate();
        if (!$success) {
            echo 'Failed to authenticate';
            exit();
        }
        $info     = $oidc->getVerifiedClaims();
        $username = $info->{$config['username_claim']};
        if (!$username) {
            echo 'No username returned';
            exit();
        }

        // at this step, the user has been authenticated by the OIDC server

        // They may be authenticated according to OIDC,
        // but that doesn't mean they have person record
        // and even if they have a person record, they may not
        // have a user account for that person record.
        try { $user = $this->auth->identify($username); }
        catch (\Exception $e) {
            $_SESSION['errorMessages'][] = $e;
            return new \Web\Views\ForbiddenView();
        }

        if (isset($user) && $user) { $_SESSION['USER'] = $user; }
        else {
            $_SESSION['errorMessages'][] = 'users/unknownUser';
            return new \Web\Views\ForbiddenView();
        }

        $return_url = $_SESSION['return_url'];
        unset($_SESSION['return_url']);
        header("Location: $return_url");
        exit();
    }
}
