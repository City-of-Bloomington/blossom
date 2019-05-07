<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People;
use Domain\People\DataStorage\PeopleRepository;

use Web\PdoRepository;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Search\SearchRequest;

class PdoPeopleRepository extends PdoRepository implements PeopleRepository
{
    const TABLE = 'people';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];
    public function columns()
    {
        return array_keys(get_class_vars('Domain\People\Entities\Person'));
    }

    public function load(int $person_id): Person
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        $select->where('id=?', $person_id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Person($result['rows'][0]);
        }
        throw new \Exception('people/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower($req->$f).'%');
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        return $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
    }

    /**
     * Saves a person and returns the ID for the person
     */
    public function save(Person $person): int
    {
        return parent::saveToTable((array)$person, self::TABLE);
    }
}
