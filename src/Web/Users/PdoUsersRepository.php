<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users;
use Domain\Users\DataStorage\UsersRepository;

use Web\PdoRepository;
use Domain\Users\Entities\User;
use Domain\Users\UseCases\Search\SearchRequest;

class PdoUsersRepository extends PdoRepository implements UsersRepository
{
    const TABLE = 'people';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];
    public function columns(): array
    {
        return array_keys(get_class_vars('Domain\Users\Entities\User'));
    }

    private function getBaseSelect()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from('people');
        $select->where('username is not null');
        return $select;
    }

    private function loadByKey(string $key, $value): ?User
    {
        $select = $this->getBaseSelect();
        $select->where("$key=?", $value);
        $result = $this->performSelect($select);
        return count($result['rows']) ? new User($result['rows'][0]) : null;
    }
    public function loadById      (int    $id      ): ?User { return $this->loadByKey('id',       $id); }
    public function loadByUsername(string $username): ?User { return $this->loadByKey('username', $username); }

    public function find(SearchRequest $req): array
    {
        $select = $this->getBaseSelect();

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f=?", $req->$f);
            }
        }
        $order = $req->order ? $req->order : self::$DEFAULT_SORT;
        $select->orderBy($order);

        echo $select->getStatement()."\n";
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
        return $result;
    }


    public function search(SearchRequest $req): array
    {
        $select = $this->getBaseSelect();

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("lower($f) like ?", strtolower("{$req->$f}%"));
            }
        }
        $order = $req->order ? $req->order : self::$DEFAULT_SORT;
        $select->orderBy($order);

        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
        return $result;
    }

    /**
     * Saves and returns the ID
     */
    public function save(User $user): int
    {
        return parent::saveToTable((array)$user, self::TABLE);
    }

    public function delete(int $id)
    {
        $sql = 'update '.self::TABLE."
                set username=null, password=null, role=null, authenticationMethod=null
                where id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$id]);
    }
}
