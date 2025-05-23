<?php
/**
 * @copyright 2022-2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Add;

use Domain\Users\Actions\Add\Request as AddRequest;

class Controller extends \Web\Controller
{
    const DEFAULT_ROLE   = 'Employee';
    const DEFAULT_AUTH   = 'Ldap';

    public function __invoke(array $params): \Web\View
    {
        $add  = $this->di->get('Domain\Users\Actions\Add\Command');
        $auth = $this->di->get('Web\Authentication\AuthenticationService');

        if (isset($_POST['username'])) {
            if ($_POST['authentication_method'] != 'local') {
                $o = $auth->externalIdentify($_POST['authentication_method'], $_POST['username']);
                if ($o) {
                    if (empty($_POST['firstname'])) { $_POST['firstname'] = $o->firstname; }
                    if (empty($_POST['lastname' ])) { $_POST['lastname' ] = $o->lastname;  }
                    if (empty($_POST['email'    ])) { $_POST['email'    ] = $o->email;     }
                }
            }

            if (!empty($_POST['password'])) {
                $_POST['password'] = $auth->password_hash($_POST['password']);
            }
            $request  = new AddRequest($_POST);
            if (!$request->role                 ) { $request->role                  = self::DEFAULT_ROLE; }
            if (!$request->authentication_method) { $request->authentication_method = self::DEFAULT_AUTH; }
            $response = $add($request);

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
            $request = new AddRequest([
                'role'                  => self::DEFAULT_ROLE,
                'authentication_method' => self::DEFAULT_AUTH
            ]);
        }

        global $ACL;
        return new View($request,
                        isset($response) ? $response : null,
                        $ACL->getRoles(),
                        $auth->getAuthenticationMethods());
    }
}
