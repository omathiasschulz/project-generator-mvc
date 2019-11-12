<?php

namespace generator;
// require_once('Helpers.php');

class GenerateConexao
{
    /**
     * Método responsável por gerar a conexao
     */
    public static function create($pdo)
    {
        $conexao = self::getConexao($pdo);
        Helpers::createFolder('app/conexao');
        Helpers::writeFile('app/conexao/Conexao.php', $conexao);
    }

    /**
     * Método que gera a string que será gravada na Conexao.php
     */
    private function getConexao($pdo)
    {
        return 
'<?php

class Conexao {

    private const DB_TYPE = "' . $pdo->driver . '";
    private const DB_HOST = "' . $pdo->host . '";
    private const DB_NAME = "' . $pdo->name . '";
    private const DB_USER = "' . $pdo->user . '";
    private const DB_PASSWORD = "' . $pdo->password . '";

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
    }
}