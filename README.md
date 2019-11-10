# ProjectGenerator

## Estrutura do Gerador (generator.json)

```
{
    "name-project": "AutoloadGenerator",
    "description": "Projeto de teste para autoload",
    "folders": [
        "app/model/bo",
        "app/model/dto",
        "app/model/dao",
        "app/view",
        "app/controller",
        "app/helpers"
    ],
    "pdo": {
        "driver": "mysql",
        "host": "127.0.0.1",
        "name": "launches",
        "user": "root",
        "password": ""
    },
    "routes": [
        {
            "url": "/",
            "controller": "Controller",
            "method": "index"
        },
        {
            "url": "/teste",
            "controller": "Controller",
            "method": "teste"
        },
        {
            "url": "/mensagem/{id}",
            "controller": "Controller",
            "method": "mensagem"
        }
    ]
}
```

## Executar o Gerador

1. Atualize o arquivo generator.json com as suas respectivas necessidades

2. Na pasta raiz do projeto execute o start.php no terminal:

`php start.php`

3. Para testar a conexão com o banco de dados, execute o index.php no terminal:

`php index.php`


## Estrutura do SQL
```
create database escola;

create table professor (
    id int not null,
    codigo int not null,
    nome varchar(100) not null,
    rg varchar(11),
    cpf varchar(11),
    dataNasc date,
    primary key(id, codigo)
);

create table aluno (
    id int not null,
    codigo int not null,
    nome varchar(100) not null,
    rg varchar(11),
    cpf varchar(11),
    dataNasc date,
    primary key(id, codigo)
);
```

## Resultado
```
array(2) {
  ["nome"]=>
  string(6) "escola"
  ["tabelas"]=>
  array(2) {
    [0]=>
    array(2) {
      ["nome"]=>
      string(9) "professor"
      ["atributos"]=>
      array(7) {
        [0]=>
        array(1) {
          ["chaves_primarias"]=>
          array(2) {
            [0]=>
            string(2) "id"
            [1]=>
            string(7) " codigo"
          }
        }
        [1]=>
        array(3) {
          ["nome"]=>
          string(2) "id"
          ["tipo"]=>
          string(3) "int"
          ["not null"]=>
          bool(true)
        }
        [2]=>
        array(3) {
          ["nome"]=>
          string(6) "codigo"
          ["tipo"]=>
          string(3) "int"
          ["not null"]=>
          bool(true)
        }
        [3]=>
        array(3) {
          ["nome"]=>
          string(4) "nome"
          ["tipo"]=>
          string(12) "varchar(100)"
          ["not null"]=>
          bool(true)
        }
        [4]=>
        array(3) {
          ["nome"]=>
          string(2) "rg"
          ["tipo"]=>
          string(11) "varchar(11)"
          ["not null"]=>
          bool(true)
        }
        [5]=>
        array(3) {
          ["nome"]=>
          string(3) "cpf"
          ["tipo"]=>
          string(11) "varchar(11)"
          ["not null"]=>
          bool(true)
        }
        [6]=>
        array(3) {
          ["nome"]=>
          string(8) "dataNasc"
          ["tipo"]=>
          string(4) "date"
          ["not null"]=>
          bool(true)
        }
      }
    }
    [1]=>
    array(2) {
      ["nome"]=>
      string(5) "aluno"
      ["atributos"]=>
      array(7) {
        [0]=>
        array(1) {
          ["chaves_primarias"]=>
          array(2) {
            [0]=>
            string(2) "id"
            [1]=>
            string(7) " codigo"
          }
        }
        [1]=>
        array(3) {
          ["nome"]=>
          string(2) "id"
          ["tipo"]=>
          string(3) "int"
          ["not null"]=>
          bool(true)
        }
        [2]=>
        array(3) {
          ["nome"]=>
          string(6) "codigo"
          ["tipo"]=>
          string(3) "int"
          ["not null"]=>
          bool(true)
        }
        [3]=>
        array(3) {
          ["nome"]=>
          string(4) "nome"
          ["tipo"]=>
          string(12) "varchar(100)"
          ["not null"]=>
          bool(true)
        }
        [4]=>
        array(3) {
          ["nome"]=>
          string(2) "rg"
          ["tipo"]=>
          string(11) "varchar(11)"
          ["not null"]=>
          bool(true)
        }
        [5]=>
        array(3) {
          ["nome"]=>
          string(3) "cpf"
          ["tipo"]=>
          string(11) "varchar(11)"
          ["not null"]=>
          bool(true)
        }
        [6]=>
        array(3) {
          ["nome"]=>
          string(8) "dataNasc"
          ["tipo"]=>
          string(4) "date"
          ["not null"]=>
          bool(true)
        }
      }
    }
  }
}
```
