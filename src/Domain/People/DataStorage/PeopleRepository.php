<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\DataStorage;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Search\Request as SearchRequest;

interface PeopleRepository
{
    public function load(int $person_id): Person;
    public function search(SearchRequest $req): array;
    public function save(Person $person): int;
}
