<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Templates\Helpers;

use Web\Helper;
use Web\Url;
use Web\View;

class Chooser extends Helper
{
	/**
	 * @param  string $fieldname   The name of the field
	 * @param  string $fieldId     The ID of the field
	 * @param  string $chooserType
	 * @param  string $value       The currently chosen value
	 * @param  string $display     Human readable representation of the current value
	 * @return string
	 */
	public function chooser( string $fieldname,
                             string $fieldId,
                             string $chooserType,
                            ?string $value   = null,
                            ?string $display = null )
    {
        $this->template->addToAsset('scripts', BASE_URI.'/js/choosers/env-'.VERSION.'.php');
		$this->template->addToAsset('scripts', BASE_URI."/js/choosers/{$chooserType}Chooser-".VERSION.".js");
		$this->template->addToAsset('scripts', BASE_URI.'/js/chooserHelper-'.VERSION.'.js');

		$CHOOSER = strtoupper($chooserType).'_CHOOSER';

		$html = "
		<input type=\"hidden\" name=\"{$fieldname}\" id=\"{$fieldId}\" value=\"$value\" />
		<span id=\"{$fieldId}-display\">$display</span>
		<button type=\"button\" class=\"chooser\"
            onclick=\"$CHOOSER.start(CHOOSER_HELPER.handleChoice, {element_id:'$fieldId', type:'$chooserType'})\">
			{$this->template->_('choose')}
		</button>
		";
		return $html;
	}
}
