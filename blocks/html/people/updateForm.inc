<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->id
 * @param string $this->firstname
 * @param string $this->lastname
 * @param string $this->email
 * @param string $this->title
 * @param string $this->return_url
 */
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>

	<form method="post">
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->id; ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$this->firstname, 'required'=>true]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname'),  'value'=>$this->lastname,  'required'=>true]);
                echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'),     'value'=>$this->email,     'required'=>true, 'type'=>'email']);

				$h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons($this->return_url);
			?>
		</fieldset>
	</form>
</section>
