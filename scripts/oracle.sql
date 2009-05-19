create table people (
	id number primary key,
	firstname varchar2(128) not null,
	lastname varchar2(128) not null,
	email varchar2(255) not null
);

create sequence people_id_seq
start with 1
increment by 1
nomaxvalue
nocache;

create trigger people_autoincrement_trigger
before insert on people
for each row
when (new.id is null)
begin
select people_id_seq.nextval INTO :new.id from dual;
end;
/

create table users (
	id number primary key,
	person_id number not null unique,
	username varchar2(30) not null unique,
	password varchar2(32),
	authenticationmethod varchar2(40) default 'LDAP' not null,
	foreign key (person_id) references people(id)
);

create sequence users_id_seq
start with 1
increment by 1
nomaxvalue
nocache;

create trigger users_autoincrement_trigger
before insert on users
for each row
when (new.id is null)
begin
select users_id_seq.nextval into :new.id from dual;
end;
/


create table roles (
	id number primary key,
	name varchar(30) not null unique
);

create sequence roles_id_seq
start with 1
increment by 1
nomaxvalue
nocache;

create trigger roles_autoincrement_trigger
before insert on roles
for each row
when (new.id is null)
begin
select roles_id_seq.nextval into :new.id from dual;
end;
/

create table user_roles (
	user_id number not null,
	role_id number not null,
	primary key (user_id,role_id),
	foreign key(user_id) references users (id),
	foreign key(role_id) references roles (id)
);
