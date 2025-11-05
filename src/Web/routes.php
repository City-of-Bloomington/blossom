<?php
/**
 * @copyright 2020-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$ROUTES = new Aura\Router\RouterContainer(BASE_URI);
$map    = $ROUTES->getMap();

$map->tokens(['id' => '\d+']);

$map->get('home.index',    '/'      , Web\HomeController::class);

$map->attach('login.', '/login', function ($r) {
    $r->get('cas',   '/cas',  Web\Auth\CAS\Controller::class);
    $r->get('oidc',  '/oidc', Web\Auth\OIDC\Controller::class);
    $r->get('login', '',      Web\Auth\Login\Controller::class);
});

$map->get('login.logout',  '/logout', Web\Auth\Logout\Controller::class);

$map->attach('people.', '/people', function ($r) {
    $r->get('update', '/update{/id}', Web\People\Update\Controller::class)->allows(['POST']);
    $r->get('view',   '/{id}'       , Web\People\Info\Controller::class);
    $r->get('index',  ''            , Web\People\List\Controller::class);
});

$map->attach('users.', '/users', function ($r) {
    $r->get('add',    '/add'        , Web\Users\Add\Controller::class)->allows(['POST']);
    $r->get('update', '/update{/id}', Web\Users\Update\Controller::class)->allows(['POST']);
    $r->get('delete', '/delete/{id}', Web\Users\Delete\Controller::class);
    $r->get('view',   '/{id}'       , Web\Users\Info\Controller::class);
    $r->get('index',  ''            , Web\Users\List\Controller::class);
});
