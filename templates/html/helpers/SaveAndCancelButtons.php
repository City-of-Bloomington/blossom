<?php
/**
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Templates\Helpers;

use Web\Helper;

class SaveAndCancelButtons extends Helper
{
	public function saveAndCancelButtons($cancelURL)
	{
		return "
		<button type=\"submit\" class=\"save\">{$this->template->_('save')}</button>
		<a href=\"$cancelURL\"  class=\"cancel\">{$this->template->_('cancel')}</a>
		";
	}
}
