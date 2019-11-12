<?php

namespace generator;

use generator\GenerateAutoload;
use generator\GenerateFolders;
use generator\GenerateConexao;
use generator\GenerateIndex;
use generator\GenerateRoutes;
use generator\GenerateController;
use helpers\SQLExtractor;

class Generate
{
    const SQL = 'sql.sql';

    /**
     * Método principal
     */
    public static function start()
    {
        $aSQL = self::getFile();
        if (!$aSQL)
            return $aSQL;
        $aDatabase = $aSQL[1];

        GenerateRoutes::create($aDatabase->tabelas);
        // GenerateController::create($aDatabase->tabelas);
        // GenerateAutoload::create(self::AUTOLOAD_NAME, $json->folders, ['conexao', 'core']);
        // GenerateFolders::create($json->folders);
        // GenerateConexao::create($json->pdo);
        // GenerateIndex::create(self::AUTOLOAD_NAME);

        // return [true, 'Projeto gerado com sucesso.'];
    }

    /**
     * Método que realiza a leitura do arquivo do arquivo SQL
     */
    private function getFile()
    {
        $path = '..' . DIRECTORY_SEPARATOR;
        return json_decode(SQLExtractor::getSQLData($path . self::SQL));
    }
}