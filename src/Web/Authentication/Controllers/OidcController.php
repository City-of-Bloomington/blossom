<?php
/**
 * @copyright 2019-2023 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication\Controllers;

use Aura\Di\Container;
use Domain\Users\Entities\User;
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
        $oidc->setRedirectURL(View::generateUrl('login.oidc'));
        $success = $oidc->authenticate();
        if (!$success) {
            return $this->failure('Failed to authenticate');
        }

        // at this step, the user has been authenticated by the OIDC server
        $info = $oidc->getVerifiedClaims();

        if (!$info->{$config['claims']['username']}) {
            return $this->failure('No username returned');
        }

        $_SESSION['USER'] = new User([
            'username'  => $info->{$config['claims']['username' ]},
            'firstname' => $info->{$config['claims']['firstname']},
            'lastname'  => $info->{$config['claims']['lastname' ]},
            'email'     => $info->{$config['claims']['email'    ]},
            'role'      => $this->role($info->{$config['claims']['groups']}, $config['claims']['groupmap']),
        ]);

        $return_url = $_SESSION['return_url'];
        unset($_SESSION['return_url']);
        header("Location: $return_url");
        exit();
    }

    private function role(array $groups, array $mapping): ?string
    {
        foreach ($mapping as $role=>$g) {
            if (in_array($g, $groups)) { return $role; }
        }
        return null;
    }

    private function failure(string $error): View
    {
        $_SESSION['errorMessages'][] = $error;
        return new \Web\Views\ForbiddenView();
    }
}
