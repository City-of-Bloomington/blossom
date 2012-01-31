<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../configuration.inc';

preg_match('|'.BASE_URI.'(/([a-zA-Z0-9]+))?(/([a-zA-Z0-9]+))?|',$_SERVER['REQUEST_URI'],$matches);
$resource = isset($matches[2]) ? $matches[2] : 'index';
$action = isset($matches[4]) ? $matches[4] : 'index';


$USER_ROLE = isset($_SESSION['USER']) ? $_SESSION['USER']->getRole() : 'Anonymous';
if ($ZEND_ACL->isAllowed($USER_ROLE, $resource, $action)) {
	$controller = ucfirst($resource).'Controller';
	$c = new $controller();
	$template = $c->$action();
}
else {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	$template = new Template();
}

if (!empty($_REQUEST['format'])) {
	$template->outputFormat = $_REQUEST['format'];
}
echo $template->render();
