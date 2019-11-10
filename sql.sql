
create database nomeBanco;

create table tabela_01 (
    chave_primaria int,
    variavel01 varchar(100),
    primary key(chave_primaria)
);

create table tabela_02    (
    chave_primaria int,   
    variavel99 decimal,
    primary key(chave_primaria)
)    ;

CREATE TABLE  tabela_03(
    chave_primaria int,
    chave_primaria2 int,
    variavel01 varchar(100),
    primary key(chave_primaria, chave_primaria2)
);