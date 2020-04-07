<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param array  $this->users  Search Results
 *
 * Query parameters that were used for the search
 * @param string $this->username
 * @param string $this->firstname
 * @param string $this->lastname
 * @param string $this->email
 * @param string $this->role
 * @param string $this->authentication_method
 *
 * Pagination
 * @param int    $this->total
 * @param int    $this->itemsPerPage
 * @param int    $this->currentPage
 *
 * Metadata
 * @param array $this->roles
 * @param array $this->authentication_methods
 */
?>
<section>
    <header>
        <h1><?= $this->_(['user', 'users', 100]); ?></h1>
    </header>
    <form method="get" action="<?= parent::generateUri('users.index'); ?>">
        <fieldset>
            <?php
                $h = $this->template->getHelper('field');

                foreach (['firstname', 'lastname', 'username', 'email'] as $f) {
                    echo $h->field([
                        'label' => $this->_($f),
                        'name'  => $f,
                        'id'    => $f,
                        'value' => $this->$f
                    ]);
                }

				$options = [['value'=>'']];
				foreach ($this->roles as $r) { $options[] = ['value'=>$r]; }
				echo $h->field([
                    'name'     => 'role',
                    'id'       => 'role',
                    'label'    => $this->_('role'),
                    'value'    => $this->role,
                    'type'     => 'select',
                    'options'  => $options
				]);


                $options = [['value'=>'']];
                foreach ($this->authentication_methods as $m) { $options[] = ['value'=>$m]; }
                echo $h->field([
                    'name'     => 'authentication_method',
                    'id'       => 'authentication_method',
                    'label'    => $this->_('authentication_method'),
                    'value'    => $this->authentication_method,
                    'type'     => 'select',
                    'options'  => $options
                ]);

                // Preserve any extra parameters passed in
                foreach ($_REQUEST as $key=>$value) {
                    if (!in_array($key, $this->vars)) {
                        $key   = self::escape($key);
                        $value = self::escape($value);
                        echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />\n";
                    }
                }
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->users) { $this->_include('users/list.inc'); }

        if ($this->total > $this->itemsPerPage) {
            $block = new Block('pageNavigation.inc', [
                'paginator' => new Paginator(
                    $this->total,
                    $this->itemsPerPage,
                    $this->currentPage
            )]);
            echo $block->render('html', $this->template);
        }
    ?>
</section>
