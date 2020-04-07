create table people (
	id        int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(128) unique,
	username  varchar(40)  unique,
	password  varchar(40),
	role      varchar(30),
	authentication_method varchar(40)
);
