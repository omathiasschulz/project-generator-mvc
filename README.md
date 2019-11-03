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

