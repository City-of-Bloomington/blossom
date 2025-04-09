<?php
/**
 * @copyright 2019-2025 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Users\Info;

use Domain\Users\Entities\User;

class View extends \Web\View
{
    public function __construct(User $user)
    {
        parent::__construct();

        $this->vars = [
            'title' => $user->getFullname(),
            'user'  => $user
        ];
    }

    public function render(): string
    {
        return $this->twig->render('html/users/info.twig', $this->vars);
    }
}
