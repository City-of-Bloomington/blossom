<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;

abstract class PdoRepository
{
    const DATE_FORMAT = 'Y-m-d';

    protected $pdo;
    protected $queryFactory;

    public function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->queryFactory = new QueryFactory(ucfirst($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)));
    }

	public function performSelect(SelectInterface $select, int $itemsPerPage=null, int $currentPage=null) : array
	{
        $total = null;

        if ($itemsPerPage) {
            $currentPage = $currentPage ? $currentPage : 1;

            $c = $this->queryFactory->newSelect();
            $c->cols(['count(*) as count'])
              ->fromSubSelect($select, 'o');

            $query = $this->pdo->prepare($c->getStatement());
            $query->execute($c->getBindValues());

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            $total  = (int)$result[0]['count'];

            $select->limit ($itemsPerPage);
            $select->offset($itemsPerPage * ($currentPage-1));
        }


        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());

        return [
            'rows'  => $query->fetchAll(\PDO::FETCH_ASSOC),
            'total' => $total
        ];
	}

	protected function saveToTable(array $data, string $table, ?string $pk='id'): int
	{
        if (!empty($data[$pk])) {
            // Update
            $id = $data[$pk];
            unset($data[$pk]);

            $update = $this->queryFactory->newUpdate();
            $update->table($table)->cols($data)->where("$pk=?", $id);
            $query = $this->pdo->prepare($update->getStatement());
            $query->execute($update->getBindValues());
            return $id;
        }
        else {
            // Insert
            unset($data[$pk]);

            $insert = $this->queryFactory->newInsert();
            $insert->into($table)->cols($data);
            $query = $this->pdo->prepare($insert->getStatement());
            $query->execute($insert->getBindValues());
            $id = $insert->getLastInsertIdName($pk);
            return (int)$this->pdo->lastInsertId($id);
        }
	}

    public function distinctFromTable(string $field, string $table): array
    {
        $select = $this->queryFactory->newSelect();
        $select->distinct()
               ->cols([$field])
               ->from($table)
               ->where("$field is not null")
               ->orderBy([$field]);

        $result = $this->pdo->query($select->getStatement());
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function doQuery(string $sql, ?array $params=null): array
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
