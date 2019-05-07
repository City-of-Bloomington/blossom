<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$conf = $DATABASES['default'];
$pdo  = new PDO("$conf[driver]:dbname=$conf[dbname];host=$conf[host]", $conf['username'], $conf['password'], $conf['options']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
if ($platform == 'Pgsql' && !empty($conf['schema'])) {
    $pdo->exec("set search_path=$conf[schema],public");
}

//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$repos = [
    'People', 'Users'
];
foreach ($repos as $t) {
    $DI->params[ "Web\\$t\\Pdo{$t}Repository"]["pdo"] = $pdo;
    $DI->set("Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Web\\$t\\Pdo{$t}Repository"));
}

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Web\Authentication\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Web\Authentication\AuthenticationService']['config'    ] = $AUTHENTICATION_METHODS;
$DI->set(    'Web\Authentication\AuthenticationService',
$DI->lazyNew('Web\Authentication\AuthenticationService'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// People
foreach(['Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\UseCases\\$a\\$a"]['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
    $DI->set(    "Domain\\People\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\People\\UseCases\\$a\\$a"));
}

// Users
foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Users\\UseCases\\$a\\$a"));
}
