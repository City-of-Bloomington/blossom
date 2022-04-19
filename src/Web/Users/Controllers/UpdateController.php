<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Controllers;
use Domain\Users\Actions\Info\Request   as InfoRequest;
use Domain\Users\Actions\Update\Request as UpdateRequest;
use Web\Users\Views\InfoView;
use Web\Users\Views\UpdateView;
use Web\Controller;
use Web\View;


class UpdateController extends Controller
{
    const DEFAULT_ROLE   = 'Employee';
    const DEFAULT_AUTH   = 'Ldap';

    public function __invoke(array $params): View
    {
        if (empty($_REQUEST['id'])) { return new \Web\Views\NotFoundView(); }

        $update = $this->di->get('Domain\Users\Actions\Update\Command');
        $auth   = $this->di->get('Web\Authentication\AuthenticationService');

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
                    return new InfoView($ir->user);
                }
                else {
                    header('Location: '.View::generateUrl('users.index'));
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
        return new UpdateView($request,
                              isset($response) ? $response : null,
                              $ACL->getRoles(),
                              $auth->getAuthenticationMethods());
    }
}
