<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Controllers;

use Web\Users\Views\ListView;

use Domain\Users\UseCases\Search\Search;
use Domain\Users\UseCases\Search\SearchRequest;

class ListController
{
    const ITEMS_PER_PAGE = 20;

    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function __invoke(array $params): ListView
    {
		$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $res  = ($this->search)(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new ListView($res, self::ITEMS_PER_PAGE, $page);
    }
}
