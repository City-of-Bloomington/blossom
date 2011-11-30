-- @copyright 2006-2010 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
create table people (
	id int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	email varchar(255) not null
);
insert people values(1,'Administrator','','');

create table authenticationMethods (
	authenticationMethod varchar(40) not null primary key
);
insert authenticationMethods values('local');
insert authenticationMethods values('Employee');

create table users (
	id int unsigned not null primary key auto_increment,
	person_id int unsigned not null,
	username varchar(30) not null unique,
	password varchar(32),
	authenticationMethod varchar(40) not null default 'Employee',
	foreign key (person_id) references people (id),
	foreign key (authenticationMethod) references authenticationMethods(authenticationMethod)
);
insert users values(1,1,'admin',md5('admin'),'local');

create table roles (
	id int unsigned not null primary key auto_increment,
	name varchar(30) not null unique
);
insert roles values(1,'Administrator');

create table user_roles (
	user_id int unsigned not null,
	role_id int unsigned not null,
	primary key (user_id,role_id),
	foreign key(user_id) references users (id),
	foreign key(role_id) references roles (id)
);
insert user_roles values(1,1);
