<?php
/**
 * @copyright 2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Auth\Views;

use Web\View;

class LoginView extends View
{
    public function render(): string
    {
        return $this->twig->render("{$this->outputFormat}/authentication/login.twig");
    }
}
