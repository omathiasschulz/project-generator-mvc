# ProjectGenerator

# Estrutura do Gerador

{

    "name-project": "nome_do_projeto",
    "description": "descricao",
    "folders": [
        "folder1",
        "folder2",
        "folder3"
    ],
    "pdo": {
        "driver": "nome_banco",
        "host": "nome_do_host",
        "name": "nome_do_db",
        "user": "nome_do_usuario",
        "password": "senha"
    }
}

# Executar o Gerador

1. Atualize o arquivo .json com as suas respectivas necessidades

2. Na pasta raiz do projeto execute o start.php no terminal:
php start.php

3. Para testar a conexão com o banco de dados, execute o index.php no terminal:
php index.php
