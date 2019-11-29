# ProjectGenerator

Gerador de CRUD (Create, Read, Update and Delete) utilizando o padrão de projeto MVC (Model, View e Controller) em PHP utilizando composer e sistema de rotas amigáveis


### Gerando o projeto

Iniciando a geração do projeto

Insira o SQL do projeto a ser gerado no arquivo sql.sql

Exemplo de um SQL permitido:

```
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
```

Após inserir o SQL execute o comando de geração do projeto no diretório ProjectGenerator

```
php start.php
```

Após executar o comando, será criado uma pasta Project, com o projeto gerado com CRUD, no padrão de projeto MVC, com conexão com o banco e rotas amigáveis

Para funcionar corretamente você deve possuir o Composer instalado e o PHP na versão 7.3.11-1


### Atualizando a configuração do XAMPP

Para o projeto a ser gerado funcionar com sucesso é necessário alterar as configurações no XAMPP, para que as rotas amigáveis funcionem corretamente

No Linux: Abra a GUI do XAMPP, clique no botão Config do apache e, em seguida, abra o arquivo 'httpd.conf'.

No Windows: Abra a GUI do XAMPP, clique no apache e, em seguida, abra as configurações. Será aberto um pequeno modal, clique em 'Open Conf File'

Vá até a configuração de Document Root, como apresentado abaixo:

```
# 
# DocumentRoot: The directory out of which you will serve your
# documents. By default, all requests are taken from this directory, but
# symbolic links and aliases may be used to point to other locations.
#
DocumentRoot "/opt/lampp/htdocs"
<Directory "/opt/lampp/htdocs">
```

Após encontrar o Document Root, altere o Document Root e o Directory para o Projeto Gerador, como apresentado abaixo:

```
DocumentRoot "/opt/lampp/htdocs/ProjectGenerator/Project"
<Directory "/opt/lampp/htdocs/ProjectGenerator/Project">
```

Após a alteração, se acessar o localhost no navegador, abrirá a página principal do projeto gerador

Exemplo de rota amigável:

```
// Rota da página principal
localhost/

// Rota que leva a tela de cadastro um novo registro 
localhost/rocket/cadastrar

// Rota que leva a tela de alteração de um registro 
localhost/rocket/{id}/atualizar

// Rota que leva a tela de visualização de um registro 
localhost/rocket/{id}/visualizar

// Rota que leva a tela de visualização com todos os registros 
localhost/rocket/listar

// Rota que recebe o id do registro e exclui do banco 
localhost/rocket/{id}/deletar
```
