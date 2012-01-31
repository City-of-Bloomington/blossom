<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../configuration.inc';
$person = new Person();
$person->setFirstname('Cliff');
$person->setLastname('Ingham');
$person->setEmail('inghamn@bloomington.in.gov');

$person->setUsername('inghamn');
//$person->setPassword();
$person->setAuthenticationMethod('Employee');
$person->setRole('Administrator');

$person->save();