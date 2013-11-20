<?php
/**
 * @copyright 2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;
use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PeopleTable
{
	private $resultSetPrototype;
	private $tableGateway;

	public function __construct()
	{
		$this->resultSetPrototype = new ResultSet();
		$this->resultSetPrototype->setArrayObjectPrototype(new Person());
		$this->tableGateway = new TableGateway(
			'people',
			Database::getConnection(),
			null,
			$this->resultSetPrototype
		);
	}

	public function getPerson($id=null)
	{
		if (ActiveRecord::isId($id)) {
			$field = 'id';
		}
		elseif (false !== strpos($id,'@')) {
			$field = 'email';
		}
		else {
			$field = 'username';
		}
		$result = $this->tableGateway->select([$field=>$id]);
		$row = $result->current();
		if (!$row) {
			throw new \Exception('people/unknownPerson.inc');
		}
		return $row;
	}

	public function find($fields=null, $paginated=false, $order='lastname', $limit=null)
	{
		$select = new Select('people');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'user_account':
						if ($value) {
							$select->where('username is not null');
						}
						else {
							$select->where('username is null');
						}
					break;

					default:
						$select->where("$key=?",$value);
				}
			}
		}
		if ($order) { $select->order($order); }
		if ($limit) { $select->limit($limit); }

		if ($paginated) {
			$adapter = new DbSelect($select, $this->tableGateway->getAdapter(), $this->resultSetPrototype);
			$paginator = new Paginator($adapter);
			return $paginator;
		}
		else {
			return $this->tableGateway->selectWith($select);
		}
	}
}
