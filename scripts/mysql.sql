create table people (
	id          int unsigned not null primary key auto_increment,
	firstname   varchar(128) not null,
	lastname    varchar(128) not null,
	displayName varchar(128),
	email       varchar(128) unique,
	username    varchar(40)  unique,
	role        varchar(30),
);
