<?php
require_once 'PHPUnit/Framework.php';

class RoleListDbTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/../testData.sql");
	}

	/**
	 * Makes sure find returns all roles ordered correctly by default
	 */
	public function testFindOrderedByName()
	{
		$PDO = Database::getConnection();
		$query = $PDO->query('select id from roles order by name');
		$result = $query->fetchAll();

		$list = new RoleList();
		$list->find();
		$this->assertEquals($list->getSort(),'name');

		foreach ($list as $i=>$role)
		{
			$this->assertEquals($role->getId(),$result[$i]['id']);
		}
    }
}
