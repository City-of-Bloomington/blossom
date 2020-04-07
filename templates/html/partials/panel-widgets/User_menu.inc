<?php
/**
 * @copyright 2015-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
?>
<nav>
    <?php
        if (isset($_SESSION['USER'])) {
            $name = self::escape("{$_SESSION['USER']->firstname} {$_SESSION['USER']->lastname}");
            echo "<header><h2>$name</h2></header>";

            $links = [
                ['url'=>parent::generateUri('login.logout' ), 'label'=>$this->_('logout'    )],
            ];
            foreach ($links as $l) {
                echo "<a href=\"$l[url]\">$l[label]</a>";
            }
        }
        else {
            $return_url = self::escape($_SERVER['REQUEST_URI']);
            $url = parent::generateUrl('login.login');
            echo "<a href=\"$url?return_url=$return_url\">{$this->_('login')}</a>";
        }
    ?>
</nav>
