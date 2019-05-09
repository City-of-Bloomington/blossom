<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Controllers;

use Domain\Auth\AuthInterface;
use Web\View;

class CasController
{
    private $auth;

    public function __construct(AuthInterface $authInterface)
    {
        $this->auth = $authInterface;
    }

    public function __invoke(): View
    {
		$return_url = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;

		// If they don't have CAS configured, send them onto the application's
		// internal authentication system
		if (!defined('CAS')) {
			header('Location: '.View::generateUrl('login.login').'?return_url='.$return_url);
			exit();
		}

		require_once CAS.'/CAS.php';
		\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
		\phpCAS::setNoCasServerValidation();
		\phpCAS::forceAuthentication();
		// at this step, the user has been authenticated by the CAS server
		// and the user's login name can be read with phpCAS::getUser().

		// They may be authenticated according to CAS,
		// but that doesn't mean they have person record
		// and even if they have a person record, they may not
		// have a user account for that person record.
		try { $user = $this->auth->identify(\phpCAS::getUser()); }
		catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }

		if (isset($user) && $user) { $_SESSION['USER'] = $user; }
		else { $_SESSION['errorMessages'][] = 'users/unknownUser'; }

        header("Location: $return_url");
        exit();

        return new \Web\Views\ForbiddenView();
    }
}
