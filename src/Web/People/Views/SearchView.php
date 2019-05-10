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
use Web\Url;

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

        $paginator = ($response->total > $itemsPerPage)
            ? new Paginator($response->total, $itemsPerPage, $currentPage)
            : null;

        if ($format == 'html') {
            $this->blocks[] = new Block('people/findForm.inc', [
                'people'        => $response->people,
                'firstname'     => !empty($_GET['firstname']) ? parent::escape($_GET['firstname']) : '',
                'lastname'      => !empty($_GET['lastname' ]) ? parent::escape($_GET['lastname' ]) : '',
                'email'         => !empty($_GET['email'    ]) ? parent::escape($_GET['email'    ]) : ''
            ]);

            if ($paginator) {
                $this->blocks[] = new Block('pageNavigation.inc', ['paginator'=>$paginator]);
            }
        }
        else {
            $model = [
                'total'     => $response->total,
                '_links'    => self::linksForPagination($paginator),
                '_embedded' => [
                    'people' => self::peopleWithLinks($response->people),
                    'errors' => $response->errors
                ]
            ];

            $this->blocks = [
                new Block('people/list.inc', ['response' => $model])
            ];
        }
    }

    private static function linksForPagination(?Paginator $paginator=null): array
    {
        $self  = Url::current_url(BASE_HOST);
        $out = ['self'=>$self];

        if ($paginator) {
            $url = new Url($self);
            $url->purgeEmptyParameters();

            $pages = $paginator->getPages();
            if (count($pages->pagesInRange) > 1) {
                $url->page = $pages->first; $out['first'] = $url->__toString();
                $url->page = $pages->prev;  $out['prev' ] = $url->__toString();
                $url->page = $pages->next;  $out['next' ] = $url->__toString();
                $url->page = $pages->last;  $out['last' ] = $url->__toString();
            }
        }
        return $out;
    }

    /**
     * @param array $people An array of Person entities
     */
    private static function peopleWithLinks(array $people): array
    {
        $out = [];
        $userCanView = parent::isAllowed('people', 'view'  );
        $userCanEdit = parent::isAllowed('people', 'update');
        foreach ($people as $i=>$p) {
            if ($userCanView) { $p->_links[] = ['self'   => parent::generateUrl('people.view',   ['id'=>$p->id])]; }
            if ($userCanEdit) { $p->_links[] = ['update' => parent::generateUrl('people.update', ['id'=>$p->id])]; }
            $out[] = $p;
        }
        return $out;
    }
}
