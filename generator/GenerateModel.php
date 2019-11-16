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
    public function create($aTables)
    {
        self::generateGenericInterface();
        
        foreach ($aTables as $oTable) {
            // Remove a primeira posição do array, que são as chaves primárias
            $aAttributes = $oTable->atributos;
            $oPrimaryKeys = array_shift($aAttributes);
            $aPrimaryKeys = $oPrimaryKeys->chaves_primarias;
            GenerateModelDto::create($oTable->nome, $aAttributes);
            GenerateModelDao::create($oTable->nome, $aAttributes, $aPrimaryKeys);
            GenerateModelBo::create($oTable->nome);
        }
    }

    /**
     * Método responsável por gerar a interface genérica
     */
    private function generateGenericInterface()
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("public function inserir(\$object);")
            ->appendNL("public function atualizar(\$object);")
            ->appendNL("public function deletar(\$object);")
            ->appendNL("public function buscarUm(\$object);")
            ->appendNL("public function buscarTodos();");

        Helpers::createClass(
            "IGeneric",
            $oBody,
            "app/model/interfaces/",
            null,
            null,
            null,
            "interface"
        );
    }
}
