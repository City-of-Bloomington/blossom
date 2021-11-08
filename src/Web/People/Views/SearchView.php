<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\Paginator;
use Web\View;

use Domain\People\Actions\Search\Request;
use Domain\People\Actions\Search\Response;

class SearchView extends View
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage)
    {
        parent::__construct();

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        $this->vars = [
            'people'      => $response->people,
            'total'       => $response->total,
            'itemsPerPage'=> $itemsPerPage,
            'currentPage' => $currentPage,
            'firstname'   => !empty($_GET['firstname']) ? parent::escape($_GET['firstname']) : '',
            'lastname'    => !empty($_GET['lastname' ]) ? parent::escape($_GET['lastname' ]) : '',
            'email'       => !empty($_GET['email'    ]) ? parent::escape($_GET['email'    ]) : ''
        ];
    }

    public function render(): string
    {
        $template = $this->outputFormat == 'html' ? 'people/findForm' : 'people/list';
        return $this->twig->render("{$this->outputFormat}/$template.twig", $this->vars);
    }
}
