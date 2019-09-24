<?php

class Helpers
{
    /**
     * Método que cria/sobreescreve um file com o conteúdo desejado
     */
    public static function writeFile($filename, $content)
    {
        $fp = fopen($filename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Método que cria um folder caso ainda não exista
     */
    public static function createFolder($pathFolder)
    {
        $completePathFolder = __DIR__ . DIRECTORY_SEPARATOR . $pathFolder;
        if (!file_exists($completePathFolder))
            mkdir($completePathFolder, 0777, true);
    }
}