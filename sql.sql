
create database transportadora;

create table produto (
    codigo int not null auto_increment,
    descricao varchar(100) not null,
    valor decimal not null,
    peso float,
    primary key(codigo)
);
