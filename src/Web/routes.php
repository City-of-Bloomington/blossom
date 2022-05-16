<?php
/**
 * @copyright 2020-2022 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$ROUTES = new Aura\Router\RouterContainer(BASE_URI);
$map    = $ROUTES->getMap();

$map->tokens(['id' => '\d+']);

$map->get('home.index',    '/'      , Web\HomeController::class);
$map->get('login.login',   '/login' , Web\Authentication\CasController::class);
$map->get('login.logout',  '/logout', Web\Authentication\LogoutController::class);

$map->attach('people.', '/people', function ($r) {
    $r->get('update', '/update{/id}', Web\People\Controllers\UpdateController::class);
    $r->get('view',   '/{id}'       , Web\People\Controllers\ViewController::class);
    $r->get('index',  ''            , Web\People\Controllers\ListController::class);
});

$map->attach('users.', '/users', function ($r) {
    $r->get('add',    '/add'        , Web\Users\Controllers\AddController::class);
    $r->get('update', '/update{/id}', Web\Users\Controllers\UpdateController::class);
    $r->get('delete', '/delete/{id}', Web\Users\Controllers\DeleteController::class);
    $r->get('view',   '/{id}'       , Web\Users\Controllers\InfoController::class);
    $r->get('index',  ''            , Web\Users\Controllers\ListController::class);
});
