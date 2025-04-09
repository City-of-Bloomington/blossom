<?php
/**
 * @copyright 2022-2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Authentication\Login;

use Aura\Di\Container;

class Controller extends \Web\Controller
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
    public function __invoke(array $params): \Web\View
    {
        $_SESSION['return_url'] = !empty($_REQUEST['return_url']) ? $_REQUEST['return_url'] : BASE_URL;
        return new View();
    }
}
