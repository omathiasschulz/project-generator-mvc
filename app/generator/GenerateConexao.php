<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateConexao
{
    /**
     * Método responsável por gerar a classe de conexao com o banco de dados
     */
    public static function create
    (
        $dbName,
        $dbType = "mysql", 
        $dbHost = "127.0.0.1",
        $dbUser = "root",
        $dbPassword = ""
    ) {
        $oBody = self::getConexao($dbName, $dbType, $dbHost, $dbUser, $dbPassword);

        Helpers::createClass(
            "Conexao",
            $oBody,
            "app/conexao/",
            ["PDO"]
        );
    }

    /**
     * Método que gera a string que será gravada na classe de conexão
     */
    private function getConexao
    (
        $dbName, 
        $dbType, 
        $dbHost, 
        $dbUser, 
        $dbPassword
    ) {
        $oBody = new StringBuilder();
        $oBody->appendNL("private const DB_TYPE = '" . $dbType . "';")
            ->appendNL("private const DB_HOST = '" . $dbHost . "';")
            ->appendNL("private const DB_NAME = '" . $dbName . "';")
            ->appendNL("private const DB_USER = '" . $dbUser . "';")
            ->appendNL("private const DB_PASSWORD = '" . $dbPassword . "';\n")
            ->appendNL("private static \$conexao;\n")
            ->appendNL("public static function startConnection()")
            ->appendNL("{")
            ->appendNL("if (isset(self::\$conexao))")
            ->appendNL("\treturn self::\$conexao;\n")
            ->appendNL("try {")
            ->appendNL("self::\$conexao = new PDO(self::DB_TYPE . ':host=' . self::DB_HOST . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASSWORD);")
            ->appendNL("self::\$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n")
            ->appendNL("return self::\$conexao;")
            ->appendNL("} catch(PDOException \$e) {")
            ->appendNL("return 'Error: ' . \$e->getMessage();")
            ->appendNL("}")
            ->appendNL("}");
        
        return $oBody;
    }
}