
create database transportadora;

use transportadora;

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
