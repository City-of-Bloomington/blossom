<?php
/**
 * @copyright 2015-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$links  = '';
$routes = [
    'people'        => 'person',
    'users'         => 'user'
];
foreach ($routes as $plural=>$singular) {
    if (parent::isAllowed($plural, 'index')) {
        $url   = parent::generateUri("$plural.index");
        $label = $this->_([$singular, $plural, 2]);
        $links.= "<a href=\"$url\">$label</a>";
    }
}
if ($links) {
    echo "
    <nav>
        <header>
            <h2>{$this->_('admin')}</h2>
        </header>
        $links
    </nav>
    ";
}
