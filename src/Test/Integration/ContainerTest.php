<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected static $di;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$di = $DI;
    }

    public function testDefaultDatabaseConnection()
    {
        $pdo    = self::$di->get('db.default');
        $sql    = 'select count(*) from people';
        $result = $pdo->query($sql);
        $count  = $result->fetchColumn();
        $this->assertGreaterThan(1, $count);
    }
}
