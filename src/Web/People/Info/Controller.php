<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Info;

use Domain\People\Entities\Person;
use Domain\People\Actions\Info\Request as InfoRequest;

class Controller extends \Web\Controller
{
    public function __invoke(array $parms): \Web\View
    {
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\People\Actions\Info\Command');
            $res  = $info((int)$_REQUEST['id']);
            if ($res->person) {
                return new View($res);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Web\Views\NotFoundView();
    }
}
