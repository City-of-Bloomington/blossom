<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

class HomeController extends Controller
{
    public function __invoke(array $params): View
    {
        return new Template('default');
    }
}

