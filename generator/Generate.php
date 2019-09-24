<?php

require_once('GenerateAutoload.php');
require_once('GenerateFolders.php');
require_once('GenerateConexao.php');
require_once('GenerateIndex.php');

class Generate
{
    const FILENAME = 'autoload.json';

    /**
     * Método principal
     */
    public static function start()
    {
        if (!file_exists(self::FILENAME))
            return [false, 'Arquivo ' . self::FILENAME . ' não existe!'];
        $json = self::getFile();

        GenerateAutoload::create($json->folders, ['conexao']);
        GenerateFolders::create($json->folders);
        GenerateConexao::create($json->pdo);
        GenerateIndex::create('autoload.php');

        return [true, 'Projeto gerado com sucesso.'];
    }

    /**
     * Método que realiza a leitura do arquivo json
     */
    private function getFile()
    {
        return json_decode(file_get_contents(self::FILENAME));
    }
}