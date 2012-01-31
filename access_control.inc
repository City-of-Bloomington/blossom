<?php
/**
 * @copyright 2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$ZEND_ACL = new Zend_Acl();
$ZEND_ACL->addRole(new Zend_Acl_Role('Administrator'))
		 ->addRole(new Zend_Acl_Role('Staff'), 'Administrator')
		 ->addRole(new Zend_Acl_Role('Public'), 'Staff')
		 ->addRole(new Zend_Acl_Role('Anonymous'), 'Public');

/**
 * Declare all the resources
 */
$ZEND_ACL->add(new Zend_Acl_Resource('index'));
$ZEND_ACL->add(new Zend_Acl_Resource('people'));
$ZEND_ACL->add(new Zend_Acl_Resource('users'));
$ZEND_ACL->add(new Zend_Acl_Resource('login'));

/**
 * Assign permissions to the resources
 */
$ZEND_ACL->allow('Administrator');
$ZEND_ACL->deny('Staff','users');
$ZEND_ACL->deny('Public','people');
$ZEND_ACL->allow('Anonymous','login');
