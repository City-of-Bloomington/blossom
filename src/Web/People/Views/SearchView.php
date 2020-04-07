<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Views;

use Web\Block;
use Web\Template;
use Web\Paginator;

use Domain\People\UseCases\Search\Request;
use Domain\People\UseCases\Search\Response;

class SearchView extends Template
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = [
            'people'       => $response->people,
            'total'        => $response->total,
            'itemsPerPage' => $itemsPerPage,
            'currentPage'  => $currentPage,
            'firstname'    => !empty($_GET['firstname']) ? parent::escape($_GET['firstname']) : '',
            'lastname'     => !empty($_GET['lastname' ]) ? parent::escape($_GET['lastname' ]) : '',
            'email'        => !empty($_GET['email'    ]) ? parent::escape($_GET['email'    ]) : ''
        ];

        $block = $format == 'html' ? 'people/findForm.inc' : 'people/list.inc';
        $this->blocks = [new Block($block, $vars)];
    }
}
