create table people (
	id          integer primary key autoincrement,
	firstname   varchar(128) not null,
	lastname    varchar(128) not null,
	displayName varchar(128),
	email       varchar(128) unique,
	username    varchar(40)  unique,
	role        varchar(30)
);
