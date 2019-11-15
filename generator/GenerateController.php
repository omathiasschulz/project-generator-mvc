<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateController 
{
    /**
     * Método responsável por gerar os controllers das tabelas
     */
    public function create($aTables)
    {
        foreach ($aTables as $oTable) {
            $oBody = self::defaultMethods($oTable->nome);

            Helpers::createClass(
                ucfirst($oTable->nome)."Controller", 
                $oBody, 
                'app/controller/',
                ["core\\AbsController", "core\\Redirecionador"],
                'AbsController'
            );
        }
    }

    /**
     * Método responsável por criar os métodos padrões de um controller
     */
    private function defaultMethods($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(self::defaultMethodCadastrar($sName))
            ->append(self::defaultMethodInserir($sName))
            ->append(self::defaultMethodVisualizar($sName))
            ->append(self::defaultMethodAtualizar($sName))
            ->append(self::defaultMethodDeletar($sName))
            ->append(self::defaultMethodListar($sName));
        
        return $oBody;
    }

    /**
     * Método responsável por gerar o método cadastrar
     * Cadastrar => Método responsável por levar para a tela de cadastro
     */
    private function defaultMethodCadastrar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("\$this->requisitarView('" . $sName . "/cadastrar', 'baseHtml');");

        return Helpers::createMethod("cadastrar", null, $oBody);
    }

    /**
     * Método responsável por gerar o método inserir
     * Inserir => Método responsável por inserir ou atualizar um registro do banco
     */
    private function defaultMethodInserir($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo inserir"
        );
        return Helpers::createMethod("inserir", "\$request", $oBody);
    }

    /**
     * Método responsável por gerar o método visualizar
     * Visualizar => Método responsável por levar a tela de visualização de um registro
     */
    private function defaultMethodVisualizar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo visualizar"
        );
        return Helpers::createMethod("visualizar", "\$id", $oBody);
    }
    
    /**
     * Método responsável por gerar o método atualizar
     * Atualizar => Método responsável por levar a tela de atualização de um registro
     */
    private function defaultMethodAtualizar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo atualizar"
        );
        return Helpers::createMethod("atualizar", "\$id", $oBody);
    }
    
    /**
     * Método responsável por gerar o método deletar
     * Deletar => Método responsável por deletar um registro
     */
    private function defaultMethodDeletar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo deletar"
        );
        return Helpers::createMethod("deletar", "\$id", $oBody);
    }
    
    /**
     * Método responsável por gerar o método listar
     * Listar => Método responsável por levar a rota de visualização do grid de registros
     */
    private function defaultMethodListar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo listar"
        );
        return Helpers::createMethod("listar", null, $oBody);
    }
}