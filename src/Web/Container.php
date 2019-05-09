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
$DI->set('Domain\Auth\AuthInterface', $DI->lazyNew('Web\Authentication\AuthenticationService'));

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
$DI->params['Domain\Users\UseCases\Update\Update']['auth'] = $DI->lazyGet('Domain\Auth\AuthInterface');
foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\UseCases\\$a\\$a"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\UseCases\\$a\\$a",
    $DI->lazyNew("Domain\\Users\\UseCases\\$a\\$a"));
}


//---------------------------------------------------------
// Controllers
//---------------------------------------------------------
$DI->set('Web\HomeController', $DI->lazyNew('Web\HomeController'));

foreach (['Web\Authentication\Controllers\CasController',
          'Web\Authentication\Controllers\LoginController'] as $controller) {
    $DI->params[$controller]['authInterface'] = $DI->lazyGet('Domain\Auth\AuthInterface');
    $DI->set($controller, $DI->lazyNew($controller));
}
$DI->set(    'Web\Authentication\Controllers\LogoutController',
$DI->lazyNew('Web\Authentication\Controllers\LogoutController'));

$DI->params[ 'Web\Users\Controllers\ListController']['search'] = $DI->lazyGet('Domain\Users\UseCases\Search\Search');
$DI->set(    'Web\Users\Controllers\ListController',
$DI->lazyNew('Web\Users\Controllers\ListController'));

$DI->params[ 'Web\Users\Controllers\UpdateController']['update'] = $DI->lazyGet('Domain\Users\UseCases\Update\Update');
$DI->params[ 'Web\Users\Controllers\UpdateController']['info'  ] = $DI->lazyGet('Domain\Users\UseCases\Info\Info');
$DI->set(    'Web\Users\Controllers\UpdateController',
$DI->lazyNew('Web\Users\Controllers\UpdateController'));

$DI->params[ 'Web\Users\Controllers\DeleteController']['delete'] = $DI->lazyGet('Domain\Users\UseCases\Delete\Delete');
$DI->set(    'Web\Users\Controllers\DeleteController',
$DI->lazyNew('Web\Users\Controllers\DeleteController'));

$DI->params[ 'Web\People\Controllers\ListController']['search'] = $DI->lazyGet('Domain\People\UseCases\Search\Search');
$DI->set(    'Web\People\Controllers\ListController',
$DI->lazyNew('Web\People\Controllers\ListController'));

$DI->params[ 'Web\People\Controllers\UpdateController']['update'] = $DI->lazyGet('Domain\People\UseCases\Update\Update');
$DI->params[ 'Web\People\Controllers\UpdateController']['info'  ] = $DI->lazyGet('Domain\People\UseCases\Info\Info');
$DI->set(    'Web\People\Controllers\UpdateController',
$DI->lazyNew('Web\People\Controllers\UpdateController'));

$DI->params[ 'Web\People\Controllers\ViewController']['info'] = $DI->lazyGet('Domain\People\UseCases\Info\Info');
$DI->set(    'Web\People\Controllers\ViewController',
$DI->lazyNew('Web\People\Controllers\ViewController'));
