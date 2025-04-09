<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Update;

use Domain\People\Actions\Update\Request as UpdateRequest;

class Controller extends \Web\Controller
{
    public function __invoke(array $params): \Web\View
    {
        $person_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        $_SESSION['return_url'] = self::defaultReturnUrl($person_id);

        if (isset($_POST['firstname'])) {
            $update  = $this->di->get('Domain\People\Actions\Update\Command');
            $req = new UpdateRequest($_POST);
            $res = $update($req);

            if (!$res->errors) {
                $return_url = $_SESSION['return_url'];
                unset($_SESSION['return_url']);
                header("Location: $return_url");
                exit();
            }
        }
        elseif ($person_id) {
            $info = $this->di->get('Domain\People\Actions\Info\Command');
            $ir   = $info($person_id);
            if ($ir->errors) {
                $_SESSION['errorMessages'] = $ir->errors;
                return new \Web\Views\NotFoundView();
            }
            $req = new UpdateRequest((array)$ir->person);
        }
        else {
            $req = new UpdateRequest();
        }

        return new View($req, isset($res) ? $res : null, $_SESSION['return_url']);
    }

    private static function defaultReturnUrl(?int $resource_id=null): string
    {
        return !empty  ($_REQUEST['return_url'])
            ? urldecode($_REQUEST['return_url'])
            : ($resource_id
                ? \Web\View::generateUrl('resources.view', ['id'=>$resource_id])
                : \Web\View::generateUrl('resources.index'));
    }
}
