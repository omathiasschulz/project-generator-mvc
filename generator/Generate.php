<?php

namespace generator;

use generator\GenerateAutoload;
use generator\GenerateFolders;
use generator\GenerateConexao;
use generator\GenerateIndex;
use generator\GenerateRoutes;
use generator\GenerateController;
use Helpers\SQLExtractor;

class Generate
{
    const SQL = '../sql.sql';
    const JSON = '../generator.json';
    const AUTOLOAD_NAME = '../autoload.php';

    /**
     * Método principal
     */
    public static function start()
    {
        if (!file_exists(__DIR__ . '/' . self::JSON))
            return [false, 'Arquivo ' . self::JSON . ' não existe!'];
        $json = self::getFile();

        var_dump($json);
        // $json->nome;
        // $json['nome'];
        // var_dump($json);
        // $aDatabase = SQLExtractor::getSQLData(self::SQL);


        GenerateAutoload::create(self::AUTOLOAD_NAME, $json->folders, ['conexao', 'core']);
        // GenerateFolders::create($json->folders);
        // GenerateConexao::create($json->pdo);
        // GenerateIndex::create(self::AUTOLOAD_NAME);
        // GenerateRoutes::create(self::AUTOLOAD_NAME, $json->routes);

        return [true, 'Projeto gerado com sucesso.'];
    }

    /**
     * Método que realiza a leitura do arquivo json
     */
    private function getFile()
    {
        return json_decode(file_get_contents(__DIR__ . '/' . self::JSON, 'r'));
    }
}