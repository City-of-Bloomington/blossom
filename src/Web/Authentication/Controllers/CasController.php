<?php
/**
 * @copyright 2019-2022 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication\Controllers;

use Aura\Di\Container;

use Web\Controller;
use Web\Template;
use Web\View;

class CasController extends Controller
{
	private $return_url;
	private $auth;

	public function __construct(Container $container)
	{
        parent::__construct($container);
        $this->auth = $this->di->get('Web\Authentication\AuthenticationService');
	}

    /**
     * Try to do CAS authentication
     */
    public function __invoke(array $params): View
    {
		$_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;

		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		global $AUTHENTICATION;
		if (empty($AUTHENTICATION['cas']['host'])) {
			$_SESSION['errorMessages'][] = 'CAS not configured';
			return new \Web\Views\NotFoundView();
		}

		$config = $AUTHENTICATION['cas'];
		\phpCAS::client(CAS_VERSION_2_0, $config['host'], 443, $config['uri'], 'https://'.BASE_HOST);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().

		// They may be authenticated according to CAS,
		// but that doesn't mean they have a person record
		// and even if they have a person record, they may not
		// have a user account for that person record.
		try { $user = $this->auth->identify(\phpCAS::getUser()); }
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
