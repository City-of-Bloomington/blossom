<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Person $this->person
 * @param string $this->title
 */
use Domain\People\Entities\Person;

$fields = ['firstname','lastname','email'];
foreach ($fields as $f) {
    $$f = parent::escape($this->person->$f);
}
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('people.update'); ?>">
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->person->id; ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$firstname, 'required'=>true]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname'),  'value'=>$lastname,  'required'=>true]);
                echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'),     'value'=>$email, 'type'=>'email']);

				$h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons(BASE_URI.'/people');
			?>
		</fieldset>
	</form>
</section>
