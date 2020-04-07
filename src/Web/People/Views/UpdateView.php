<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\People\Views;

use Domain\People\UseCases\Update\Request;
use Domain\People\UseCases\Update\Response;
use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct(Request $request, ?Response $response, string $return_url)
    {
        parent::__construct('default', 'html');

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = $request->id ? $this->_('person_edit') : $this->_('person_add');

        $vars = [
            'title'      => $this->vars['title'],
            'return_url' => $return_url
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        $this->blocks = [ new Block('people/updateForm.inc', $vars) ];
    }
}
