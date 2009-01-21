<?php
require_once 'PHPUnit/Framework.php';

class RoleDbTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/../testData.sql");
	}

    public function testSaveLoad()
    {
		$role = new Role();
		$role->setName('Test Role');
    	try {
			$role->save();
			$id = $role->getId();
			$this->assertGreaterThan(0,$id);
		}
		catch (Exception $e) {
			$this->fail($e->getMessage());
		}

		$role = new Role($id);
		$this->assertEquals($role->getName(),'Test Role');

		$role->setName('Test');
		$role->save();

		$role = new Role($id);
		$this->assertEquals($role->getName(),'Test');
    }
}
