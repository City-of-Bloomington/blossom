<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Domain\Users\UseCases\Update\Request;
use Domain\Users\UseCases\Update\Response;
use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct(Request   $request,
                                ?Response $response,
                                array     $roles,
                                array     $authentication_methods)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = $request->id ? $this->_('user_edit') : $this->_('user_add');

        $vars = [
            'title'                  => $this->vars['title'],
            'request'                => $request,
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods
        ];

        $this->blocks = [
            new Block('users/updateForm.inc', $vars)
        ];
    }
}
