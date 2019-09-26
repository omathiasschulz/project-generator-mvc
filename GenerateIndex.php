<?php

require_once('Helpers.php');

class GenerateIndex
{
    /**
     * Método responsável por gerar o index de teste
     */
    public static function create($autoloadFilename)
    {
        $index = self::getIndex($autoloadFilename);
        Helpers::writeFile('index.php', $index);
    }

    /**
     * Método que gera a string que será gravada no index.php
     */
    private function getIndex($autoloadFilename)
    {
        return 
'<?php

require_once("' . $autoloadFilename . '");
require_once("app/conexao/Conexao.php");

if (Conexao::startConnection())
    echo "Conexão efetuada com sucesso!";
else
    echo "Erro ao conectar ao banco!";
';
    }
}
