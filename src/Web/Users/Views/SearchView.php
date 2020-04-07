<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Web\Block;
use Web\Template;
use Web\Paginator;

use Domain\Users\UseCases\Search\Request;
use Domain\Users\UseCases\Search\Response;

class SearchView extends Template
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage,
                                array    $roles,
                                array    $authentication_methods)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        $vars = [
            'users'                  => $response->users,
            'total'                  => $response->total,
            'roles'                  => $roles,
            'authentication_methods' => $authentication_methods,
        ];
        foreach ($request as $k=>$v) {
            if (!is_array($v)) { $vars[$k] = parent::escape($v); }
        }
        $block = $format == 'html' ? 'users/findForm.inc' : 'users/list.inc';
        $this->blocks = [new Block($block, $vars)];
    }
}
