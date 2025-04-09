<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Update;

use Domain\People\Actions\Update\Request;
use Domain\People\Actions\Update\Response;

class View extends \Web\View
{
    public function __construct(Request $request, ?Response $response, string $return_url)
    {
        parent::__construct();

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars = [
            'title'     => $request->id ? $this->_('person_edit') : $this->_('person_add'),
            'return_url'=> $return_url
        ];

        foreach ($request as $k=>$v) { $this->vars[$k] = $v; }
    }

    public function render(): string
    {
        return $this->twig->render("{$this->outputFormat}/people/updateForm.twig", $this->vars);
    }
}
