<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\People\Views\InfoView;
use Web\View;

use Domain\People\UseCases\Info\Info;
use Domain\People\UseCases\Info\InfoRequest;

class ViewController extends Controller
{
    private $info;

    public function __construct(Info $info)
    {
        $this->info = $info;
    }

    public function __invoke(array $params): View
    {
        if (!empty($_REQUEST['id'])) {
            $req  = new InfoRequest((int)$_REQUEST['id']);
            $res  = ($this->info)($req);
            if ($res->person) {
                return new InfoView($res);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }
}
