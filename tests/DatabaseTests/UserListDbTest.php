<?php
require_once 'PHPUnit/Framework.php';

class UserListDbTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/../testData.sql");
	}

	/**
	 * Makes sure find returns all users ordered correctly by default
	 */
	public function testFindOrderedByName()
	{
		$PDO = Database::getConnection();
		$query = $PDO->query('select id from users order by username');
		$result = $query->fetchAll();

		$list = new UserList();
		$list->find();
		$this->assertEquals($list->getSort(),'username');

		foreach ($list as $i=>$user)
		{
			$this->assertEquals($user->getId(),$result[$i]['id']);
		}
    }
}
