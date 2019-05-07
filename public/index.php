<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
/**
 * Grab a timestamp for calculating process time
 */
$startTime = microtime(true);

include '../bootstrap.php';

$url   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $ROUTES->match($url, $_SERVER);
if ($route) {
    if (isset($route->params['controller'])) {
        $class      = $route->params['controller'];
        $controller = new $class($DI);
       
        // Permissions are based on the route names
        list($resource, $permission) = explode('.', $route->name);
        $role = isset($_SESSION['USER']) ? $_SESSION['USER']->role : 'Anonymous';
        if (   $ZEND_ACL->hasResource($resource)
            && $ZEND_ACL->isAllowed($role, $resource, $permission)) {
            if (!empty($route->params['id'])) {
                    $_GET['id'] = $route->params['id'];
                $_REQUEST['id'] = $route->params['id'];
            }
            $view = $controller($route->params);
        }
        else {
            $view = new \Web\Views\ForbiddenView();
        }
    }
    else {
        die('No controller');
    }
}
else {
    $f    = $ROUTES->getFailedRoute();
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
        $factor = floor((strlen((string)$bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    $memory = human_filesize(memory_get_peak_usage());
    echo "<!-- Memory: $memory -->";
}
