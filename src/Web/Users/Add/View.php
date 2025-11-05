<?php
/**
 * @copyright 2022-2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Add;

use Domain\Users\Actions\Add\Request;
use Domain\Users\Actions\Add\Response;


class View extends \Web\View
{
    public function __construct(Request   $request,
                                ?Response $response)
    {
        parent::__construct();

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars = array_merge((array)$request, [
            'title' => $this->_('user_add'),
            'roles' => self::roles(),
        ]);
    }

    public function render(): string
    {
        return $this->twig->render('html/users/addForm.twig', $this->vars);
    }

    private static function roles(): array
    {
        global $ACL;
        return $ACL->getRoles();
    }
}
