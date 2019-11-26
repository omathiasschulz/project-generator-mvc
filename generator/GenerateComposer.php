<?php

class GenerateComposer
{
    const GLOBAL_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * Método responsável por gerar o composer.json
     */
    public static function create()
    {
        $sBody = self::getBody();
        $sFilename = 'composer.json';

        $completeFilename = self::GLOBAL_PATH . $sFilename;
        $fp = fopen($completeFilename, 'w');
        fwrite($fp, $sBody);
        fclose($fp);

        // Executa o comando de geração do composer
        exec("composer update");
    }

    /**
     * Método que gera a string que será gravada no composer.json
     */
    private function getBody()
    {
        $sBody = 
              "{\n"
            . "\t\"name\": \"projectGenerator/projectGenerator\",\n"
            . "\t\"description\": \"projectGenerator\",\n"
            . "\t\"type\": \"project\",\n"
            . "\t\"license\": \"MIT\",\n"
            . "\t\"autoload\": {\n"
            . "\t\t\"psr-4\": {\n"
            . "\t\t\t\"helpers\\\\\": [\n"
            . "\t\t\t\t\"helpers/\"\n"
            . "\t\t\t],\n"
            . "\t\t\t\"generator\\\\\": [\n"
            . "\t\t\t\t\"generator/\"\n"
            . "\t\t\t],\n"
            . "\t\t\t\"core\\\\\": [\n"
            . "\t\t\t\t\"core/\"\n"
            . "\t\t\t],\n"
            . "\t\t\t\"app\\\\\": [\n"
            . "\t\t\t\t\"app/\"\n"
            . "\t\t\t]\n"
            . "\t\t}\n"
            . "\t},\n"
            . "\t\"require\": {\n"
            . "\t\t\"components/bootstrap\": \"4.3.*\"\n"
            . "\t}\n"
            . "}\n";
            
        return $sBody;
    }
}
