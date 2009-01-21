<?php
require_once 'PHPUnit/Framework.php';

class UserUnitTest extends PHPUnit_Framework_TestCase
{
	public function testValidate()
	{
		$user = new User();

		try {
			$user->validate();
			$this->fail('Empty User failed to throw validation exception');
		}
		catch (Exception $e) { }

		$user->setUsername('test');
		$user->setFirstname('Test');
		$user->setLastname('User');

		$user->validate();
	}
}
