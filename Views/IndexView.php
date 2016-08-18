<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views;

class IndexView extends BaseView
{
    public function render()
    {
        echo $this->twig->render('index.html', $this->vars);
    }
}
