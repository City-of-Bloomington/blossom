<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class PeopleController
{
	public function index()
	{
		$people = new PersonList();
		$people->find();

		$template = new Template();
		$template->blocks[] = new Block('people/personList.inc',array('personList'=>$people));
		return $template;
	}

	public function view()
	{
		try {
			$person = new Person($_REQUEST['person_id']);
		}
		catch (Exception $e) {
			$_SESSION['errorMessages'][] = $e;
		}

		$template = new Template();
		$template->blocks[] = new Block('people/personInfo.inc',array('person'=>$person));
		return $template;
	}

	public function update()
	{
		$person = isset($_REQUEST['person_id']) ? new Person($_REQUEST['person_id']) : new Person();

		if (isset($_POST['firstname'])) {
			$person->set($_POST);
			try {
				$person->save();
				header('Location: '.BASE_URL.'/people');
				exit();
			}
			catch (Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		$template = new Template();
		$template->blocks[] = new Block('people/updatePersonForm.inc',array('person'=>$person));
		return $template;
	}
}