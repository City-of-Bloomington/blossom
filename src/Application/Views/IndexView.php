<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views;

class IndexView extends BaseView
{
    public function render()
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        echo $this->twig->render("index.$format.twig", $this->vars);
    }
}
