<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;
use generator\GenerateModelDto;
use generator\GenerateModelDao;

class GenerateModel
{
    /**
     * Método responsável por gerar os models
     */
    public function create($aTabelas)
    {
        foreach ($aTabelas as $oTabela) {
            // Remove a primeira posição do array, que são as chaves primárias
            $oChavesPrimarias = array_shift($oTabela->atributos);
            $aChavesPrimarias = $oChavesPrimarias->chaves_primarias;
            GenerateModelDto::create($oTabela->nome, $oTabela->atributos);
            GenerateModelDao::create($oTabela->nome, $oTabela->atributos, $aChavesPrimarias);
        }
    }
}
