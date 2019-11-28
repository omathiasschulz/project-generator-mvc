<?php

namespace generator;

class GenerateView
{
    /**
     * Método responsável por gerar as view
     */
    public function create($aTables)
    {
        foreach ($aTables as $oTable) {
            // Remove a primeira posição do array, que são as chaves primárias
            $aAttributes = $oTable->atributos;
            $oPrimaryKeys = array_shift($aAttributes);
            $aPrimaryKeys = $oPrimaryKeys->chaves_primarias;

            for ($i=1; $i < count($aAttributes); $i++) { 
                echo('');
            }
        }
    }
}
