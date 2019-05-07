<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\UseCases\Search;

use Domain\People\DataStorage\PeopleRepository;
use Domain\People\Entities\Person;

class Search
{
    private $repo;

    public function __construct(PeopleRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            $people = [];
            foreach ($result['rows'] as $row) { $people[] = new Person($row); }
            return new SearchResponse($people, $result['total']);
        }
        catch (\Exception $e) {
            return new SearchResponse([], null, [$e->getMessage()]);
        }
    }
}
