<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Auth\Logout;

class Controller extends \Web\Controller
{
    public function __invoke()
    {
		session_destroy();
		header('Location: '.BASE_URL);
		exit();
    }
}
