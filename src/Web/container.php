<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$DI->set('db.default', \Web\Database::getConnection($DATABASES['default'], 'default'));

//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$repos = [
    'People', 'Users',
];
foreach ($repos as $t) {
    $DI->params[ "Web\\$t\\Pdo{$t}Repository"]["pdo"] = $DI->lazyGet('db.default');
    $DI->set("Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Web\\$t\\Pdo{$t}Repository"));
}

//---------------------------------------------------------
// Metadata providers
//---------------------------------------------------------

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Web\Authentication\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Web\Authentication\AuthenticationService']['config'    ] = $LDAP;
$DI->set(    'Web\Authentication\AuthenticationService',
$DI->lazyNew('Web\Authentication\AuthenticationService'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// People
foreach(['Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\Actions\\$a\\Command"]['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
    $DI->set(    "Domain\\People\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\People\\Actions\\$a\\Command"));
}

// Users
foreach (['Add', 'Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\Actions\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\Actions\\$a\\Command",
    $DI->lazyNew("Domain\\Users\\Actions\\$a\\Command"));
}
