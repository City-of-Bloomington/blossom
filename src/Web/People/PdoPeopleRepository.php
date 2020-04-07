<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\People;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\People\DataStorage\PeopleRepository;
use Domain\People\Entities\Person;
use Domain\People\UseCases\Search\Request as SearchRequest;
use Web\PdoRepository;


class PdoPeopleRepository extends PdoRepository implements PeopleRepository
{
    const TABLE = 'people';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];
    public function columns()
    {
        static $columns;
        if (!$columns) { $columns = array_keys(get_class_vars('Domain\People\Entities\Person')); }
        return $columns;
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

    public static function hydrate(array $row): Person { return new Person($row); }

    /**
     * Look for people using wildcard matching of fields
     */
    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower($req->$f).'%');
            }
        }

        return parent::performHydratedSelect($select,
                                             __CLASS__.'::hydrate',
                                             self::$DEFAULT_SORT,
                                             $req->itemsPerPage,
                                             $req->currentPage);
    }

    /**
     * Saves a person and returns the ID for the person
     */
    public function save(Person $person): int
    {
        return parent::saveToTable((array)$person, self::TABLE);
    }

    private function doSelect(SelectInterface $select, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $result = parent::performSelect($select, self::$DEFAULT_SORT, $itemsPerPage, $currentPage);

        $people = [];
        foreach ($result['rows'] as $r) { $people[] = new Person($r); }
        $result['rows'] = $people;
        return $result;
    }
}
