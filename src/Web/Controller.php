<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

use Aura\Di\Container;

class Controller
{
    protected $di;

    public function __construct(Container $container)
    {
        $this->di = $container;
    }
}
