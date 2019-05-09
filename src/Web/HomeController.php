<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

use Web\View;

class HomeController
{
    public function __invoke(array $params): View
    {
        return new Template('default');
    }
}
