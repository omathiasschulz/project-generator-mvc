# ProjectGenerator

### Gerando o projeto

Iniciando a geração do projeto

Execute o comando abaixo no diretório ProjectGenerator para buscar as dependências do ProjectGenerator

```
cd app/ && composer update
```

Insira o SQL do projeto a ser gerado no arquivo sql.sql

Exemplo de um SQL permitido:

```
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
    dataCompra year,
    primary key(codigo)
);
```

Após inserir o SQL execute o comando de geração do projeto no diretório ProjectGenerator

```
php start.php
```
