<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateIndex
{
    /**
     * Método responsável por gerar o index
     */
    public static function create()
    {
        $oBody = self::getBody();
        Helpers::writeFile('index.php', $oBody);
    }

    /**
     * Método que gera a string que será gravada no index.php
     */
    private function getBody()
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("<?php\n")
            ->appendNL("require_once 'vendor/autoload.php';")
            ->appendNL("require_once 'core/bootstrap.php';");

        return $oBody;
    }
}
