<?php
/**
 * @copyright 2023 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Controllers;

use Web\Controller;
use Web\View;

class DeleteController extends Controller
{
    public function __invoke(array $params): View
    {
        $delete = $this->di->get('Domain\Users\Actions\Delete\Command');
        $res    = $delete((int)$params['id']);
        if ($res->errors) {
            $_SESSION['errorMessages'] = $res->errors;
        }
        header('Location: '.View::generateUrl('users.index'));
        exit();
    }
}
