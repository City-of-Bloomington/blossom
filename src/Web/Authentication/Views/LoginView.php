<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->return_url
 */
declare (strict_types=1);
namespace Web\Authentication\Views;

use Web\Block;
use Web\Template;

class LoginView extends Template
{
    public function __construct(string $return_url)
    {
        parent::__construct('default', 'html');

        $this->blocks = [
            new Block('loginForm.inc', ['return_url'=>$return_url])
        ];
    }
}
