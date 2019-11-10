
create database escola;

create table professor (
    id int not null,
    codigo int not null,
    nome varchar(100) not null,
    rg varchar(11),
    cpf varchar(11),
    dataNasc date,
    primary key(id, codigo, nome)
);

create table aluno (
     id  int not null,
    codigo int not null,
    nome varchar(100) not null,
    rg varchar(11),
    cpf varchar(11),
    dataNasc date,
    primary key(id, codigo)
);