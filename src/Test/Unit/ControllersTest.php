<?php
/**
 * @copyright 2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use PHPUnit\Framework\TestCase;

class ControllersTest extends TestCase
{
    public function testControllers(): void
    {
        global $DI, $ROUTES;

        foreach ($ROUTES->getMap()->getRoutes() as $r) {
            $class = $r->handler;
            $c = new $class($DI);
            $this->assertInstanceOf($class, $c);
        }
    }
}
