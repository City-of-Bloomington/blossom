<?php
/**
 * @copyright 2022 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Views;

use Domain\Users\Actions\Add\Request;
use Domain\Users\Actions\Add\Response;

use Web\View;

class AddView extends View
{
    public function __construct(Request   $request,
                                ?Response $response,
                                array     $roles,
                                array     $authentication_methods)
    {
        parent::__construct();

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars = array_merge((array)$request, [
            'title'                  => $this->_('user_add'),
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods
        ]);
    }

    public function render(): string
    {
        return $this->twig->render('html/users/addForm.twig', $this->vars);
    }
}
