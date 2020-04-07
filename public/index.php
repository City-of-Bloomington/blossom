<?php
/**
 * @copyright 2015-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
/**
 * Grab a timestamp for calculating process time
 */
declare (strict_types=1);
use Web\Authentication\Auth;

$startTime = microtime(true);

include '../src/Web/bootstrap.php';


$p = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $ROUTES->match($p, $_SERVER);
if ($route) {
    if (isset($route->params['controller'])) {
        $controller = $route->params['controller'];
        $c = new $controller($DI);
        if (is_callable($c)) {
            $user = Auth::getAuthenticatedUser($DI->get('Web\Authentication\AuthenticationService'));
            if (Auth::isAuthorized($route->name, $user)) {
                if (!empty($route->params['id'])) {
                        $_GET['id'] = $route->params['id'];
                    $_REQUEST['id'] = $route->params['id'];
                }
                $view = $c($route->params);
            }
            else {
                if     ( isset($_SESSION['USER'])
                    || (!empty($_REQUEST['format']) && $_REQUEST['format'] != 'html')) {
                    $view = new \Web\Views\ForbiddenView();
                }
                else {
                    header('Location: '.\Web\View::generateUrl('login.login'));
                    exit();
                }
            }
        }
        else {
            $f = $ROUTES->getFailedRoute();
            $view = new \Web\Views\NotFoundView();
        }
    }
}
else {
    $f = $ROUTES->getFailedRoute();
    $view = new \Web\Views\NotFoundView();
}


echo $view->render();

if ($view->outputFormat === 'html') {
    # Calculate the process time
    $endTime = microtime(true);
    $processTime = $endTime - $startTime;
    echo "<!-- Process Time: $processTime -->\n";

    function human_filesize(int $bytes, ?int $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen("$bytes") - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    $memory = human_filesize(memory_get_peak_usage());
    echo "<!-- Memory: $memory -->";
}
