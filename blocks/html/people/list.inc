<?php
/**
 * @copyright 2009-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $people
 */
use Application\Models\Person;
use Blossom\Classes\View;
?>
<div class="interfaceBox">
	<h2><?php
			echo $this->translate(['labels.person','labels.people',2]);

			$helper = $this->template->getHelper('buttonLink');

			if (Person::isAllowed('people')) {
				echo $helper->buttonLink(
					BASE_URI.'/people/update',
					$this->translate('labels.add_person'),
					'add'
				);
			}
		?>
	</h2>
	<table>
		<thead>
			<tr><th></th>
				<th><?php echo $this->_('labels.username'); ?></th>
				<th><?php echo $this->_('labels.name'); ?></th>
				<th><?php echo $this->_(['labels.email','labels.emails',1]); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->people as $person) {
				$editButton = '';
				if (Person::isAllowed('people')) {
					$editButton = $helper->buttonLink(
						BASE_URI.'/people/update?person_id='.$person->getId(),
						$this->translate('labels.edit'),
						'edit'
					);
				}

				$name = View::escape($person->getFullname());
				echo "
				<tr><td>$editButton</td>
					<td>{$person->getUsername()}</td>
					<td><a href=\"{$person->getURL()}\">$name</a></td>
					<td>{$person->getEmail()}</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
