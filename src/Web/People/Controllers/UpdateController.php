<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\People\Views\UpdateView;
use Web\View;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Update\UpdateRequest;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_REQUEST['return_url'])) {
            $_SESSION['return_url'] = urldecode($_REQUEST['return_url']);
        }

        if (isset($_POST['firstname'])) {
            $update   = $this->di->get('Domain\People\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                $return_url = !empty($_SESSION['return_url'])
                            ? $_SESSION['return_url']
                            : parent::generateUrl('people.view', ['id'=>$response->id]);
                unset($_SESSION['return_url']);
                header('Location: '.$return_url);
                exit();
            }
            $person = new Person((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\People\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res    = $info($req);
                $person = $res->person;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $person = new Person(); }

        return new UpdateView($person, isset($response) ? $response : null);
    }
}
