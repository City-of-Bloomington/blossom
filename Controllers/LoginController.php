<?php
/**
 * @copyright 2012-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Person;
use Auth0\SDK\Auth0;
use Blossom\Classes\Controller;

class LoginController extends Controller
{
	private $return_url;

	public function __construct()
	{
		$this->return_url = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;
	}

	public function auth0(array $params)
	{
        $auth0 = new Auth0([
            'domain'        => 'bloomington.auth0.com',
            'client_id'     => 'zADAd1zOQCoAMUqAKe1LtGLxv6HesaXR',
            'client_secret' => 'XGSrESnb12En5TGjAoRIfqW5jvGAmBkOa5YH1ezTfuTflpHsdrxPxYPAwCRPHvxq',
            'redirect_uri'  => 'https://aoi.bloomington.in.gov/blossom/login/auth0',
            'audience'      => 'https://bloomington.auth0.com/userinfo',
            'scope'         => 'openid profile'
        ]);
        $user = $auth0->getUser();
        if ($user) {
            $this->registerUser($user['name']);
        }
        else {
            $auth0->login();
        }
	}

	/**
	 * Attempts to authenticate users via CAS
	 */
	public function cas(array $params)
	{
		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		if (defined('CAS')) {
            require_once CAS.'/CAS.php';
            \phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
            \phpCAS::setNoCasServerValidation();
            \phpCAS::forceAuthentication();
            // at this step, the user has been authenticated by the CAS server
            // and the user's login name can be read with phpCAS::getUser().

            $this->registerUser(\phpCAS::getUser());
        }

        header('Location: '.self::generateUrl('login.index').'?return_url='.$this->return_url);
        exit();
	}

	/**
	 * Checks for a user account with the given username.
	 * If they exist it will register the user into the session and redirect.
	 * Writes to $_SESSION[errorMessages] if there's a problem.
	 */
	private function registerUser(string $username)
	{
        try {
            $_SESSION['USER'] = new Person($username);
            header("Location: {$this->return_url}");
            exit();
        }
        catch (\Exception $e) {
            $_SESSION['errorMessages'][] = $e;
        }
	}

	/**
	 * Attempts to authenticate users based on AuthenticationMethod
	 */
	public function index(array $params)
	{
		if (isset($_POST['username'])) {
			try {
				$person = new Person($_POST['username']);
				if ($person->authenticate($_POST['password'])) {
					$_SESSION['USER'] = $person;
					header('Location: '.$this->return_url);
					exit();
				}
				else {
					throw new \Exception('invalidLogin');
				}
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		return new \Application\Views\Login\LoginView([
            'return_url'=>$this->return_url
        ]);
	}

	public function logout(array $params)
	{
		session_destroy();
		header('Location: '.$this->return_url);
		exit();
	}
}
