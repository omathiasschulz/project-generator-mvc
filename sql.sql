create database launches;

use launches;

create table rocket (
    id int not null auto_increment,
    rocket_id varchar(50) not null,
    rocket_name varchar(50) not null,
    description varchar(500) not null,
    first_flight date not null,
    last_flight datetime not null,
    height double not null,
    diameter double not null,
    mass double  not null,
    primary key (id)
);

create table mission (
	id int not null auto_increment,
    mission_id varchar(50),
    name varchar(50),
    description  varchar(100),
    primary key (id)
);

create table launch (
	id int not null auto_increment,
    flight_number varchar(50),
    date date,
    description varchar(100) not null,
    primary key (id)
);
