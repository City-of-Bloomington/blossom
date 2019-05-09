<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Controllers;

class LogoutController
{
    public function __invoke()
    {
		session_destroy();
		\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
		\phpCAS::logout();

		$return_url = !empty($_REQUEST['return_url'])
                    ? $_REQUEST['return_url']
                    : BASE_URL;
		header("Location: $return_url");
		exit();
    }
}
