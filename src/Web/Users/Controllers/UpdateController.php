<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Controllers;

use Web\Controller;
use Web\Users\Views\UpdateView;
use Web\View;

use Domain\Users\UseCases\Info\InfoRequest;
use Domain\Users\UseCases\Update\UpdateRequest;

class UpdateController extends Controller
{
    const DEFAULT_ROLE   = 'Public';
    const DEFAULT_AUTH   = 'local';

    public function __invoke(array $params): View
    {
        if (isset($_POST['firstname'])) {
            $update   = $this->di->get('Domain\Users\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            if (!$request->role                 ) { $request->role                  = self::DEFAULT_ROLE; }
            if (!$request->authentication_method) { $request->authentication_method = self::DEFAULT_AUTH; }
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('users.index'));
                exit();
            }
            $user = new User((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Users\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $user = $res->user;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $user = new User(); }

        return new UpdateView($user, isset($response) ? $response : null);
    }
}
