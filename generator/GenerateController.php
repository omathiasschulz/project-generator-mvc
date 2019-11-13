<?php

namespace generator;

use core\AbsController;
use helpers\Helpers;
use helpers\StringBuilder;

class GenerateController 
{
    /**
     * Método responsável por gerar os controllers
     */
    public function create($aTabelas)
    {
        foreach ($aTabelas as $key => $oTabela) {
            $sBody = self::defaultMethods($oTabela->nome);

            Helpers::createClass(
                ucfirst($oTabela->nome)."Controller", 
                $sBody, 
                'app/controller/',
                ["core\\AbsController", "core\\Redirecionador"],
                'AbsController'
            );
        }
    }

    /**
     * Método responsável por criar os métodos padrões de um controller
     */
    private function defaultMethods($sTableName)
    {
        $sBody = ""
            . self::defaultMethodCadastrar($sTableName);
        
        return $sBody;
    }

    /**
     * Método responsável por gerar o método cadastrar
     * Cadastrar => Método responsável por levar para a tela de cadastro
     */
    private function defaultMethodCadastrar($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t\$this->requisitarView('" . $sTableName . "/cadastrar', 'baseHtml');"
        );
        return Helpers::createMethod('cadastrar', null, $body);
    }

    // /**
    // * Método responsável por inserir ou atualizar um registro do banco
    // */
    // public function inserir($request) {}
    
    // /**
    // * Método responsável por levar a tela de visualização de um registro
    // */
    // public function visualizar($id) {}
    
    // /**
    // * Método responsável por levar a tela de atualização de um registro
    // */
    // public function atualizar($id) {}
    
    // /**
    // * Método responsável por deletar um registro
    // */
    // public function deletar($id) {}

    // /**
    //  * Método responsável por levar a rota de visualização do grid de registros
    //  */
    // public function listar() {}
}