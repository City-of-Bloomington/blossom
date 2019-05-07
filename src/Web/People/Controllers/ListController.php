<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\Controllers;

use Web\Controller;
use Web\People\Views\SearchView;
use Web\View;

use Domain\People\UseCases\Search\SearchRequest;

class ListController extends Controller
{
    const ITEMS_PER_PAGE = 20;
    
    public function __invoke(array $params): View
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\People\UseCases\Search\Search');
        $res    = $search(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new SearchView($res, self::ITEMS_PER_PAGE, $page);
    }
}
