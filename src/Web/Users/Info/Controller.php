<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Info;

class Controller extends \Web\Controller
{
    public function __invoke(array $params): \Web\View
    {
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Users\Actions\Info\Command');
            $res  = $info((int)$_REQUEST['id']);
            if ($res->user) {
                return new View($res->user);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Web\Views\NotFoundView();
    }
}
