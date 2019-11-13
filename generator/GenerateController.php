<?php

namespace generator;

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
            . self::defaultMethodCadastrar($sTableName)
            . self::defaultMethodInserir($sTableName)
            . self::defaultMethodVisualizar($sTableName)
            . self::defaultMethodAtualizar($sTableName)
            . self::defaultMethodDeletar($sTableName)
            . self::defaultMethodListar($sTableName);
        
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
        return Helpers::createMethod("cadastrar", null, $body);
    }

    /**
     * Método responsável por gerar o método inserir
     * Inserir => Método responsável por inserir ou atualizar um registro do banco
     */
    private function defaultMethodInserir($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t// metodo inserir"
        );
        return Helpers::createMethod("inserir", "\$request", $body);
    }

    /**
     * Método responsável por gerar o método visualizar
     * Visualizar => Método responsável por levar a tela de visualização de um registro
     */
    private function defaultMethodVisualizar($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t// metodo visualizar"
        );
        return Helpers::createMethod("visualizar", "\$id", $body);
    }
    
    /**
     * Método responsável por gerar o método atualizar
     * Atualizar => Método responsável por levar a tela de atualização de um registro
     */
    private function defaultMethodAtualizar($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t// metodo atualizar"
        );
        return Helpers::createMethod("atualizar", "\$id", $body);
    }
    
    /**
     * Método responsável por gerar o método deletar
     * Deletar => Método responsável por deletar um registro
     */
    private function defaultMethodDeletar($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t// metodo deletar"
        );
        return Helpers::createMethod("deletar", "\$id", $body);
    }
    
    /**
     * Método responsável por gerar o método listar
     * Listar => Método responsável por levar a rota de visualização do grid de registros
     */
    private function defaultMethodListar($sTableName)
    {
        $body = new StringBuilder();
        $body->append(
            "\t\t// metodo listar"
        );
        return Helpers::createMethod("listar", null, $body);
    }
}