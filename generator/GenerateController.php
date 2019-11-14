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
        $body = new StringBuilder();
        $body->append(self::defaultMethodCadastrar($sTableName))
            ->append(self::defaultMethodInserir($sTableName))
            ->append(self::defaultMethodVisualizar($sTableName))
            ->append(self::defaultMethodAtualizar($sTableName))
            ->append(self::defaultMethodDeletar($sTableName))
            ->append(self::defaultMethodListar($sTableName));
        
        return $body;
    }

    /**
     * Método responsável por gerar o método cadastrar
     * Cadastrar => Método responsável por levar para a tela de cadastro
     */
    private function defaultMethodCadastrar($sTableName)
    {
        $body = new StringBuilder();
        $body->append("\$this->requisitarView('" . $sTableName . "/cadastrar', 'baseHtml');");

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
            "// metodo inserir"
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
            "// metodo visualizar"
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
            "// metodo atualizar"
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
            "// metodo deletar"
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
            "// metodo listar"
        );
        return Helpers::createMethod("listar", null, $body);
    }
}