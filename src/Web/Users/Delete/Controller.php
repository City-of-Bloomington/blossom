<?php
/**
 * @copyright 2023-2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Delete;

class Controller extends \Web\Controller
{
    public function __invoke(array $params): \Web\View
    {
        $delete = $this->di->get('Domain\Users\Actions\Delete\Command');
        $res    = $delete((int)$params['id']);
        if ($res->errors) {
            $_SESSION['errorMessages'] = $res->errors;
        }
        header('Location: '.\Web\View::generateUrl('users.index'));
        exit();
    }
}
