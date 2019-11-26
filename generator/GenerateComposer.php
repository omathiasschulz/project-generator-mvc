<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateComposer
{
    /**
     * Método responsável por gerar o composer.json
     */
    public static function create()
    {
        $oBody = self::getBody();
        Helpers::writeFile('composer.json', $oBody);

        // Executa o comando de geração do composer
        exec("composer update");
    }

    /**
     * Método que gera a string que será gravada no composer.json
     */
    private function getBody()
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("{")
            ->appendNL("\t\"name\": \"projectGenerator/projectGenerator\",")
            ->appendNL("\t\"description\": \"projectGenerator\",")
            ->appendNL("\t\"type\": \"project\",")
            ->appendNL("\t\"license\": \"MIT\",")
            ->appendNL("\t\"autoload\": {")
            ->appendNL("\t\t\"psr-4\": {")
            ->appendNL("\t\t\t\"helpers\\\": [")
            ->appendNL("\t\t\t\t\"helpers/\"")
            ->appendNL("\t\t\t],")
            ->appendNL("\t\t\t\"generator\\\": [")
            ->appendNL("\t\t\t\t\"generator/\"")
            ->appendNL("\t\t\t],")
            ->appendNL("\t\t\t\"core\\\": [")
            ->appendNL("\t\t\t\t\"core/\"")
            ->appendNL("\t\t\t],")
            ->appendNL("\t\t\t\"app\\\": [")
            ->appendNL("\t\t\t\t\"app/\"")
            ->appendNL("\t\t\t]")
            ->appendNL("\t\t}")
            ->appendNL("\t},")
            ->appendNL("\t\"require\": {")
            ->appendNL("\t\t\"components/bootstrap\": \"4.3.*\"")
            ->appendNL("\t}")
            ->appendNL("}");
            
        return $oBody;
    }
}
