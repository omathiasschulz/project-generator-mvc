<?php

namespace generator;

use generator\GenerateRoutes;
use generator\GenerateController;
use helpers\SQLExtractor;

class Generate
{
    const SQL = 'sql.sql';
    const TYPES_DATA = ['date', 'time', 'datetime', 'year'];

    /**
     * Método principal
     */
    public static function start()
    {
        $aSQL = self::getFile();
        if (!$aSQL[0])
            return $aSQL;
        $aDatabase = $aSQL[1];

        GenerateRoutes::create($aDatabase->tabelas);
        GenerateConexao::create($aDatabase->nome);
        GenerateController::create($aDatabase->tabelas);
        GenerateModel::create($aDatabase->tabelas, self::TYPES_DATA);
        GenerateCore::create();

        return [true, 'Projeto gerado com sucesso.'];
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