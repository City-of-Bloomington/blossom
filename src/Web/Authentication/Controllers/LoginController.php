<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Controllers;

use Domain\Auth\AuthInterface;

class LoginController
{
    $this->auth;

    public function __construct(AuthInterface $authInterface)
    {
        $this->auth = $authInterface;
    }

    public function __invoke(): View
    {
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
