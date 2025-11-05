<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\List;

use Domain\Users\Actions\Search\Request;
use Domain\Users\Actions\Search\Response;

class View extends \Web\View
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage)
    {
        parent::__construct();

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }


        $this->vars = array_merge((array)$request, [
            'users' => $response->users,
            'total' => $response->total,
            'roles' => self::roles(),
        ]);

        $fields = array_keys((array)$request);
        foreach ($_REQUEST as $k=>$v) {
            if (!in_array($k, $fields)) {
                $this->vars['additional_params'][$k] = $v;
            }
        }
    }

    public function render(): string
    {
        return $this->twig->render('html/users/findForm.twig', $this->vars);
    }

    private static function roles(): array
    {
        global $ACL;
        return $ACL->getRoles();
    }
}
