<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Controllers;

use Web\Authentication\Views\LoginView;

use Domain\Auth\AuthInterface;

class LoginController
{
    private $auth;

    public function __construct(AuthInterface $authInterface)
    {
        $this->auth = $authInterface;
    }

    public function __invoke(): LoginView
    {
		$return_url = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;

		if (isset($_POST['username'])) {
			try {
                $_SESSION['USER'] = $this->auth->authenticate($_POST['username'], $_POST['password']);
                header("Location: $return_url");
                exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		return new LoginView($return_url);
    }
}
