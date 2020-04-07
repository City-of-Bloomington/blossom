<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param User $this->user
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header><h1><?= parent::escape($this->user->getFullname()); ?></h1></header>
    <?php
        $actions = [];
        if (parent::isAllowed('users', 'update')) {
            $actions['user_edit'] = [
                'uri'   => parent::generateUri("users.update", ['id'=>$this->user->id]),
                'class' => 'edit'
            ];
        }
        if (parent::isAllowed('users', 'delete')) {
            $actions['user_delete'] = [
                'uri'   => parent::generateUri("users.delete", ['id'=>$this->user->id]),
                'class' => 'delete'
            ];
        }
        if ($actions) {
            $block = new Block('toolbox.inc', ['actions' => $actions]);
            echo $block->render('html', $this->template);
        }
    ?>
    <table>
    <?php
        $fields = ['username', 'email'];
        foreach ($fields as $f) {
            $v = parent::escape($this->user->$f);
            echo "<tr><th>{$this->_($f)}</th><td>$v</td></tr>";
        }
    ?>
    </table>
</section>
