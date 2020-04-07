<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->firstname
 * @param string $this->lastname
 * @param string $this->email
 * @param array  $this->people         Array of people search results
 * @param int    $this->total
 * @param int    $this->itemsPerPage
 * @param int    $this->currentPage
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_(['person', 'people', 100]); ?></h1>
    </header>
    <form method="get">
        <fieldset>
        <?php
            $helper = $this->template->getHelper('field');
            $fields = ['firstname', 'lastname', 'email'];
            foreach ($fields as $f) {
                echo $helper->field([
                    'name'  => $f,
                    'id'    => $f,
                    'label' => $this->_($f),
                    'value' => $this->$f
                ]);
            }
        ?>
        <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->firstname || $this->lastname || $this->email) {
            $return_url = $_SERVER['REQUEST_URI'];
            $helper = $this->template->getHelper('buttonLink');

            if (parent::isAllowed('people', 'add')) {
                echo $helper->buttonLink(
                    parent::generateUri('people.update')."?return_url=$return_url",
                    $this->translate('person_add'),
                    'add'
                );
            }

            if (!$this->people) {
                echo '<div>';
                echo sprintf($this->_('no_results', 'messages'), $this->_(['person', 'people', 2]));
                echo '</div>';
            }
        }
        if ($this->people) {
            $this->_include('people/list.inc');
        }

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
