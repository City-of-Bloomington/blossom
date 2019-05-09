<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\People\Views\SearchView;
use Web\View;

use Domain\People\UseCases\Search\Search;
use Domain\People\UseCases\Search\SearchRequest;

class ListController
{
    const ITEMS_PER_PAGE = 20;

    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function __invoke(array $params): View
    {
		$page =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $res  = ($this->search)(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new SearchView($res, self::ITEMS_PER_PAGE, $page);
    }
}
