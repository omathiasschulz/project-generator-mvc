<?php

namespace generator;

class GenerateModel
{
    /**
     * Método responsável por gerar os models
     */
    public function create($aTabelas)
    {
        var_dump($aTabelas);
        echo $aTabelas[0]->nome;
    }
}
