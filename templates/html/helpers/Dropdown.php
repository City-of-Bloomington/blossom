<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Templates\Helpers;

use Web\Helper;

class Dropdown extends Helper
{
	public function dropdown(array $links, $title, $id, $class=null)
	{
        $html = "
        <details class=\"dropdown $class\">
            <summary>$title</summary>
            <nav>
                {$this->renderLinks($links)}
            </nav>
        </details>
        ";
        return $html;
	}

	private function renderLinks(array $links)
	{
        $html = '';
        foreach ($links as $l) {

            $attrs = '';
            if (!empty($l['attrs'])) {
                $attrs = ' ';
                foreach ($l['attrs'] as $key=>$value) {
                    $attrs.= "$key=\"$value\"";
                }
            }

            $html.= "<a href=\"$l[url]\"$attrs>$l[label]</a>";
        }
        return $html;
	}
}
