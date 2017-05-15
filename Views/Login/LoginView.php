<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Login;

use Application\Views\BaseView;

class LoginView extends BaseView
{
    public function render()
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        echo $this->twig->render("loginForm.$format.twig", $this->vars);
    }
}
