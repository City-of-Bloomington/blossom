<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Update;

use Domain\Users\Actions\Update\Request;
use Domain\Users\Actions\Update\Response;

class View extends \Web\View
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
            'title'                  => $this->_('user_edit'),
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods
        ]);
    }

    public function render(): string
    {
        return $this->twig->render('html/users/updateForm.twig', $this->vars);
    }
}
