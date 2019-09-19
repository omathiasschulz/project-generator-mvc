<?php


$filename = 'autoload.json';

if (file_exists($filename)) {
    $json = json_decode(file_get_contents('autoload.json'));

    // CRIAÇÃO DO AUTOLOAD
    $folders = '[ "conexao", ';
    foreach ($json->folders as $folder)
        $folders .= '"' . $folder . '", ';
    $folders = substr($folders, 0, strlen($folders) - 2) . ']';
    
    $autoload =
'<?php
spl_autoload_register(function ($nomeClasse) {
    $folders = ' . $folders . ';
    foreach ($folders as $folder) {
        if (file_exists($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php")) {
            require_once($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php");
        }
    }
});';
    $fp = fopen('autoload.php', 'w');
    fwrite($fp, $autoload);
    fclose($fp);

    // CRIACAO DAS PASTAS
    $arrayFolders = [];
    foreach ($json->folders as $folder)
        if (!file_exists($folder))
            mkdir(__DIR__ . '/' . $folder, 0777, true);
    
    // CRIACAO DA CONEXAO
    $conexao = 
'<?php

class Conexao {

    private const DB_TYPE = "' . $json->pdo->driver . '";
    private const DB_HOST = "' . $json->pdo->host . '";
    private const DB_NAME = "' . $json->pdo->name . '";
    private const DB_USER = "' . $json->pdo->user . '";
    private const DB_PASSWORD = ' . ($json->pdo->password != "" ? '"'.$json->pdo->password.'"' : '""') . ';

    // instance
    private static $conexao;

    // getInstance
    public static function startConnection()
    {
        if (isset(self::$conexao))
            return self::$conexao;
        
        try {
            self::$conexao = new PDO(self::DB_TYPE . ":host=" . self::DB_HOST . ";dbname=" . self::DB_NAME, self::DB_USER, self::DB_PASSWORD);
            self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$conexao;
            
        } catch(PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}';

    if (!file_exists(__DIR__ . '/conexao'))
        mkdir(__DIR__ . '/conexao', 0777, true);
    $fp = fopen('conexao/Conexao.php', 'w');
    fwrite($fp, $conexao);
    fclose($fp);

    // CRIACAO DO TESTE DE CONEXAO
    $index = 
'<?php

require_once "autoload.php";

if (Conexao::startConnection())
    echo "Conexão efetuada com sucesso!";
else
    echo "Erro ao conectar ao banco!";

';
    $fp = fopen('testeConexao.php', 'w');
    fwrite($fp, $index);
    fclose($fp);
    
} else {
    echo "O arquivo $filename não existe";
}
