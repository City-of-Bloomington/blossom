<?php
/**
 * @copyright 2013-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Templates\Helpers;

use Blossom\Classes\Template;

class SaveAndCancelButtons
{
	private $template;

	public function __construct(Template $template)
	{
		$this->template = $template;
	}

	public function saveAndCancelButtons($cancelURL)
	{
		return "
		<button type=\"submit\" class=\"save\">{$this->template->_('labels.save')}</button>
		<a href=\"$cancelURL\"  class=\"cancel\">{$this->template->_('labels.cancel')}</a>
		";
	}
}
