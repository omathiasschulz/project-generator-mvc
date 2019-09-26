<?php

require_once('Helpers.php');

class GenerateFolders
{
    /**
     * Método responsável por gerar os folders
     */
    public static function create($aFolders)
    {
        foreach ($aFolders as $folder) {
            Helpers::createFolder($folder);
        }
    }
}