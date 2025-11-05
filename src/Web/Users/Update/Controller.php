<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Update;

use Domain\Users\Actions\Info\Request   as InfoRequest;
use Domain\Users\Actions\Update\Request as UpdateRequest;

class Controller extends \Web\Controller
{
    const DEFAULT_ROLE   = 'Employee';
    const DEFAULT_AUTH   = 'Ldap';

    public function __invoke(array $params): \Web\View
    {
        if (empty($_REQUEST['id'])) { return new \Web\Views\NotFoundView(); }

        $update = $this->di->get('Domain\Users\Actions\Update\Command');
        $auth   = $this->di->get('Web\Auth\AuthenticationService');

        if (isset($_POST['id'])) {
            $request  = new UpdateRequest($_POST);
            if (!$request->role                 ) { $request->role                  = self::DEFAULT_ROLE; }
            if (!$request->authentication_method) { $request->authentication_method = self::DEFAULT_AUTH; }
            $response = $update($request);
            if ($response->errors) {
                $_SESSION['errorMessages'] = $response->errors;
            }
            else {
                if (!empty($_REQUEST['format']) && $_REQUEST['format']!='html') {
                    $info = $this->di->get('Domain\Users\Actions\Info\Command');
                    $ir   = $info($response->id);
                    return new \Web\Users\Info\View($ir->user);
                }
                else {
                    header('Location: '.\Web\View::generateUrl('users.index'));
                    exit();
                }
            }
        }
        else {
            $info = $this->di->get('Domain\Users\Actions\Info\Command');
            $res  = $info((int)$_REQUEST['id']);
            if ($res->errors) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Web\Views\NotFoundView();
            }
            $request = new UpdateRequest((array)$res->user);
        }

        global $ACL;
        return new View($request,
                        isset($response) ? $response : null,
                        $ACL->getRoles(),
                        $auth->getAuthenticationMethods());
    }
}
