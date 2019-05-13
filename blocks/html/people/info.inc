<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->name
 * @param string $this->email
 * @param array  $this->links  Actions that can be taken on this person
 */
?>
<section>
    <header>
        <h1><?= $this->name; ?></h1>
        <?php
            if ($this->links) {
                $h = $this->template->getHelper('buttonLink');

                echo '<div class="tools">';
                foreach ($this->links as $label=>$url) {
                    echo $h->buttonLink($url, $this->_($label), $label);
                }
                echo '</div>';
            }
        ?>
    </header>

	<table>
        <tr><th><?= $this->_('email'); ?></th>
            <td><?= $this->email;      ?></td>
        </tr>
	</table>
</section>
