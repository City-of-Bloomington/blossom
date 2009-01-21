<?php
require_once 'PHPUnit/Framework.php';

class UserDbTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/../testData.sql");
	}

    public function testSaveLoad()
    {
		$user = new User();
		$user->setName('Test User');
    	try {
			$user->save();
			$id = $user->getId();
			$this->assertGreaterThan(0,$id);
		}
		catch (Exception $e) {
			$this->fail($e->getMessage());
		}

		$user = new User($id);
		$this->assertEquals($user->getName(),'Test User');

		$user->setName('Test');
		$user->save();

		$user = new User($id);
		$this->assertEquals($user->getName(),'Test');
    }
}
