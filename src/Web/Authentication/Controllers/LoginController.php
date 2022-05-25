<?php
/**
 * @copyright 2022 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication\Controllers;

use Aura\Di\Container;

use Web\Authentication\Views\LoginView;
use Web\Controller;
use Web\Template;
use Web\View;

class LoginController extends Controller
{
    private $return_url;
    private $auth;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->auth = $this->di->get('Web\Authentication\AuthenticationService');
    }

    /**
     * Try to do CAS authentication
     */
    public function __invoke(array $params): View
    {
        $_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;
        return new LoginView();
    }
}
