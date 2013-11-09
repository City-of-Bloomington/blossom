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
	<h2>People
		<?php
			if (Person::isAllowed('people')) {
				echo "<a class=\"add button\" href=\"".BASE_URI."/people/update\">Add a Person</a>";
			}
		?>
	</h2>
	<table>
		<thead>
			<tr><th></th>
				<th>Username</th>
				<th>Name</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->people as $person) {
				$editButton = '';
				if (Person::isAllowed('people')) {
					$editButton = "
					<a class=\"edit button\"
						href=\"".BASE_URI."/people/update?person_id={$person->getId()}\">
						Edit
					</a>
					";
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
