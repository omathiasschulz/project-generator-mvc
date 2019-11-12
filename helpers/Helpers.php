<?php

namespace helpers;

class Helpers
{
    const GLOBAL_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * Método que cria/sobreescreve um file com o conteúdo desejado
     */
    public static function writeFile($filename, $content)
    {
        $completeFilename = self::GLOBAL_PATH . $filename;
        $fp = fopen($completeFilename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
    /**
     * Método que cria um folder caso ainda não exista
     * Deve ser passado o diretório a partir da pasta principal do projeto
     */
    public static function createFolder($pathFolder)
    {
        $completePathFolder = self::GLOBAL_PATH . $pathFolder;
        if (!file_exists($completePathFolder)) {
            mkdir($completePathFolder, 0777, true);
        }
    }
}