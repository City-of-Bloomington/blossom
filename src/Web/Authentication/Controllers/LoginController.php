<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Controllers;

use Web\Controller;

class LoginController extends Controller
{
    public function __invoke(): View
    {
		$auth = $this->di->get('Web\Authentication\AuthenticationService');
		if (isset($_POST['username'])) {
			try {
                $_SESSION['USER'] = $this->auth->authenticate($_POST['username'], $_POST['password']);
                header('Location: '.$this->return_url);
                exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		return new LoginView(['return_url'=>$this->return_url]);
    }
}
