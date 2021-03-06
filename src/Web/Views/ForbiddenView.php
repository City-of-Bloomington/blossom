<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Views;

use Web\Template;

class ForbiddenView extends Template
{
    public function __construct(array $vars=null)
    {
        header('HTTP/1.1 403 Forbidden', true, 403);

        parent::__construct('default', 'html', $vars);
        $_SESSION['errorMessages'][] = isset($_SESSION['USER'])
            ? 'noAccessAllowed'
            : 'notLoggedIn';
    }
}
