<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->links  An HAL _links array
 */
declare (strict_types=1);
?>
<nav>
    <header><h1><?= $this->_('actions'); ?></h1></header>
    <?php
        foreach ($this->actions as $k=>$l) {
            echo "<a href=\"$l[href]\">$k</a>";
        }
    ?>
</nav>
