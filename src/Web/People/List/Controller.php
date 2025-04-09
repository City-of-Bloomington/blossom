<?php
/**
 * Returns a list of people
 *
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People\List;

use Domain\People\Actions\Search\Request as SearchRequest;

class Controller extends \Web\Controller
{
    public function __invoke(array $params): View
    {
		$page     =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search   = $this->di->get('Domain\People\Actions\Search\Command');
        $request  = new SearchRequest($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = $search($request);

        return new View($request, $response, parent::ITEMS_PER_PAGE, $page);
    }
}
