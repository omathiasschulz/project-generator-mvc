<?php

require_once('GenerateAutoload.php');
require_once('GenerateFolders.php');
require_once('GenerateConexao.php');
require_once('GenerateIndex.php');
require_once('GenerateRoutes.php');

class Generate
{
    const JSON = 'generator.json';
    const AUTOLOAD_NAME = 'autoload.php';

    /**
     * Método principal
     */
    public static function start()
    {
        if (!file_exists(__DIR__ . '/' . self::JSON))
            return [false, 'Arquivo ' . self::JSON . ' não existe!'];
        $json = self::getFile();

        GenerateAutoload::create(self::AUTOLOAD_NAME, $json->folders, ['conexao', 'core']);
        GenerateFolders::create($json->folders);
        GenerateConexao::create($json->pdo);
        GenerateIndex::create(self::AUTOLOAD_NAME);
        GenerateRoutes::create(self::AUTOLOAD_NAME, $json->routes);

        return [true, 'Projeto gerado com sucesso.'];
    }

    /**
     * Método que realiza a leitura do arquivo json
     */
    private function getFile()
    {
        return json_decode(file_get_contents(self::JSON, 'r'));
    }
}