<?php
/**
 * @copyright 2012-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Person;

include '../bootstrap.inc';
$person = new Person();
$person->setFirstname('{{blossom_user_fname}}');
$person->setLastname('{{blossom_user_lname}}');
$person->setEmail('{{blossom_user_email}}');

$person->setUsername("{{ ansible_ssh_user }}");
//$person->setPassword();
$person->setAuthenticationMethod('Employee');
$person->setRole('Administrator');

$person->save();
