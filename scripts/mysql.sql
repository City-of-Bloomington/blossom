-- @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
CREATE TABLE users (
	id int unsigned not null primary key auto_increment,
	username varchar(30) not null unique,
	password varchar(32),
	authenticationMethod varchar(40) not null default 'LDAP',
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	email varchar(255) not null
) engine=InnoDB;

CREATE TABLE roles (
	id int unsigned not null primary key auto_increment,
	name varchar(30) not null unique
) engine=InnoDB;
insert roles values(1,'Administrator');

CREATE TABLE user_roles (
	user_id int unsigned not null,
	role_id int unsigned not null,
	primary key (user_id,role_id),
	foreign key(user_id) references users (id),
	foreign key(role_id) references roles (id)
) engine=InnoDB;
