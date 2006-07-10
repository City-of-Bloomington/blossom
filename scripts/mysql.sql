-- @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- This file is part of the City of Bloomington's web application Framework.
-- This Framework is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This Framework is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with Foobar; if not, write to the Free Software
-- Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

CREATE TABLE users (
	id int unsigned auto_increment NOT NULL,
	username varchar(30) UNIQUE,
	password varchar(32),
	authenticationMethod varchar(40) DEFAULT 'LDAP' NOT NULL,
	firstname varchar(128) NOT NULL,
	lastname varchar(128) NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE roles (
	id int unsigned auto_increment NOT NULL,
	role varchar(30) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE user_roles (
	user_id int unsigned NOT NULL,
	role_id int unsigned NOT NULL,
	primary key (user_id,role_id),
	FOREIGN KEY(user_id) REFERENCES users (id),
	FOREIGN KEY(role_id) REFERENCES roles (id)) engine=InnoDB;
