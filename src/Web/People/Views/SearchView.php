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

use Domain\People\UseCases\Search\SearchResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse $response, int $itemsPerPage, int $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('people_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format == 'html') {
            $this->blocks[] = new Block('people/findForm.inc', [
                'people'        => $response->people,
                'firstname'     => !empty($_GET['firstname']) ? parent::escape($_GET['firstname']) : '',
                'lastname'      => !empty($_GET['lastname' ]) ? parent::escape($_GET['lastname' ]) : '',
                'email'         => !empty($_GET['email'    ]) ? parent::escape($_GET['email'    ]) : ''
            ]);

            if ($response->total > $itemsPerPage) {
                $this->blocks[] = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $response->total,
                        $itemsPerPage,
                        $currentPage
                )]);
            }
        }
        else {
            $this->blocks = [
                new Block('people/list.inc', ['people'=>$response->people])
            ];
        }
    }
}
