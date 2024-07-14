drop database if exists dolphin_crm; /* create database if not exists dolphin_crm; */
create database dolphin_crm;
use dolphin_crm;

drop table if exists users;
create table users ( /* if not exists */
    id int(11) not null auto_increment,
    firstname varchar(30) not null default "",
    lastname varchar(30) not null default "",
    password varchar(65) not null default "",
    email varchar(30) not null default "",
    role varchar(15) not null default "",
    created_at datetime not null default current_timestamp,
    primary key (id)
);

drop table if exists contacts;
create table contacts (
    id int(11) not null auto_increment,
    title varchar(10) not null default "",
    firstname varchar(30) not null default "",
    lastname varchar(30) not null default "",
    email varchar(30) not null default "",
    telephone varchar(15) not null default "",
    company varchar(35) not null default "",
    type varchar(15) not null default "",
    assigned_to integer not null,
    created_by integer not null,
    created_at datetime not null default current_timestamp,
    updated_at datetime not null default current_timestamp on update current_timestamp,
    primary key (id)
);

drop table if exists notes;
create table notes (
    id int(11) not null auto_increment,
    contact_id integer not null,
    comment text not null,
    created_by integer not null,
    created_at datetime not null default current_timestamp,
    primary key (id)
);

insert into users (firstname, lastname, password, email, role) values ("Admin", "User", md5("password123"), "admin@project2.com", "Admin");
