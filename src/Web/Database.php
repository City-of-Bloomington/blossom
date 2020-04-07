<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

class Database
{
    public static function getConnection(string $name='default', array $config): \PDO
    {
        switch ($name) {
            default:
                return self::connectDefault($config);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    private static function connectDefault(array $conf): \PDO
    {
        try {
            $pdo = new \PDO("$conf[driver]:dbname=$conf[name];host=$conf[host]", $conf['user'], $conf['pass'], $conf['opts']);
        }
        catch (\Exception $e) {
            die("Could not connect to default database server\n");
        }
        return $pdo;
    }
}
