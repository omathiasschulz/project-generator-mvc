CREATE DATABASE LAUNCHES;

USE LAUNCHES;

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

create table produto (
    codigo int not null auto_increment,
    descricao varchar(100) not null,
    valorBruto decimal(8,3) not null,
    valorLiquido decimal,
    peso float,
    pesoEmbalado float,
    dataFabricacao datetime not null,
    dataCompra datetime,
    primary key(codigo)
);
