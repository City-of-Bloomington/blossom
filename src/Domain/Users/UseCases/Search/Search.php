<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\UseCases\Search;

use Domain\Users\DataStorage\UsersRepository;
use Domain\Users\Entities\User;

class Search
{
    private $repo;

    public function __construct(UsersRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(SearchRequest $req): SearchResponse
    {
        try {
            $result = $this->repo->search($req);
            $users = [];
            foreach ($result['rows'] as $row) { $users[] = new User($row); }
            return new SearchResponse($users, $result['total']);
        }
        catch (\Exception $e) {
            return new SearchResponse([], null, [$e->getMessage()]);
        }
    }
}
