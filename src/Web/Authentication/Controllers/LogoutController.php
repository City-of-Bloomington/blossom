<?php
/**
 * @copyright 2019-2022 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication\Controllers;

use Web\Controller;
use Web\Template;
use Web\View;

class LogoutController extends Controller
{
    public function __invoke()
    {
		session_destroy();
		header('Location: '.BASE_URL);
		exit();
    }
}
