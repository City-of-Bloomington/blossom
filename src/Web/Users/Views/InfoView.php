<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Views;

use Domain\Users\Entities\User;

use Web\Block;
use Web\Template;

class InfoView extends Template
{
    public function __construct(User $user)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = parent::escape($user->getFullname());

        $this->blocks = [
            new Block('users/info.inc', ['user'=>$user])
        ];
    }
}
