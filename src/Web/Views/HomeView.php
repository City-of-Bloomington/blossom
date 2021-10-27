<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Views;

use Web\View;

class HomeView extends View
{
    public function render()
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        echo $this->twig->render("index.$format.twig", $this->vars);
    }
}
