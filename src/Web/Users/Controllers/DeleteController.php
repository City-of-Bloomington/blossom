<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Controllers;

use Web\Controller;
use Web\View;

use Domain\Users\UseCases\Delete\DeleteRequest;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_REQUEST['id'])) {
            $delete = $this->di->get('Domain\Users\UseCases\Delete\Delete');
            $req    = new DeleteRequest((int)$_REQUEST['id']);
            $res    = $delete($req);
            if (count($res->errors)) {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        else {
            $_SESSION['errorMessages'][] = 'users/unknown';
        }

		header('Location: '.View::generateUrl('users.index'));
		exit();
    }
}
