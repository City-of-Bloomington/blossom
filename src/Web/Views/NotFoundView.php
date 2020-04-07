<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Views;

use Web\Block;
use Web\Template;

class NotFoundView extends Template
{
    public function __construct(array $vars=null)
    {
        header('HTTP/1.1 404 Not Found', true, 404);

        parent::__construct('default', 'html', $vars);
        $this->blocks[] = new Block('404.inc');
    }
}
