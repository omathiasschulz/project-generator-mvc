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
            // Remove a primeira posição do array, que são as chaves primárias
            $aAttributes = $oTable->atributos;
            $oPrimaryKeys = array_shift($aAttributes);
            $aPrimaryKeys = $oPrimaryKeys->chaves_primarias;
            $oBody = self::defaultMethods($oTable->nome, $aAttributes, $aPrimaryKeys);

            Helpers::createClass(
                ucfirst($oTable->nome)."Controller", 
                $oBody, 
                'app/controller/',
                [
                    "core\\AbsController", 
                    "core\\Redirecionador",
                    "app\\model\\dto\\".ucfirst($oTable->nome),
                    "app\\model\\dao\\".ucfirst($oTable->nome)."DAO",
                    "app\\model\\bo\\".ucfirst($oTable->nome)."BO",
                ],
                'AbsController'
            );
        }
    }

    /**
     * Método responsável por criar os métodos padrões de um controller
     */
    private function defaultMethods($sName, $aAttributes, $aPrimaryKeys)
    {
        $oBody = new StringBuilder();
        $oBody->append(self::defaultMethodCadastrar($sName))
            ->append(self::defaultMethodInserir($sName, $aAttributes, $aPrimaryKeys))
            ->append(self::defaultMethodAtualizar($sName))
            ->append(self::defaultMethodAlterar($sName))
            ->append(self::defaultMethodVisualizar($sName))
            ->append(self::defaultMethodListar($sName))
            ->append(self::defaultMethodDeletar($sName))
            ;
        
        return $oBody;
    }

    /**
     * Método responsável por gerar o método cadastrar
     * Cadastrar => Método responsável por levar a tela de cadastro um novo registro 
     */
    private function defaultMethodCadastrar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("\$this->requisitarView('" . $sName . "/cadastrar', 'baseHtml');");

        return Helpers::createMethod("cadastrar", null, $oBody);
    }

    /**
     * Método responsável por gerar o método inserir
     * Inserir => Método responsável por receber o request com o registro e inserir no banco 
     */
    private function defaultMethodInserir($sName, $aAttributes, $aPrimaryKeys)
    {
        $oFieldsSet = new StringBuilder();
        foreach ($aAttributes as $oAttribute)
            if (!in_array($oAttribute->nome, $aPrimaryKeys)) 
                $oFieldsSet->appendNL(
                    "\t->set" . ucfirst($oAttribute->nome) . "(isset(\$request->post->" . $oAttribute->nome . ") ? \$request->post->" . $oAttribute->nome . " : '')"
                );
        
        $oBody = new StringBuilder();
        $oBody->appendNL("\$" . $sName . "BO  = new " . ucfirst($sName) . "BO((new " . ucfirst($sName) . "DAO()));")
            ->appendNL("\$" . $sName . " = (new " . ucfirst($sName) . "())")
            ->appendNL($oFieldsSet . ";")
            ->appendNL("\$result = \$" . $sName . "BO->inserir(\$" . $sName . ");")
            ->append("Redirecionador::paraARota('cadastrar?cadastrado=' . \$result);")
        ;

        return Helpers::createMethod("inserir", "\$request", $oBody);
    }

    /**
     * Método responsável por gerar o método atualizar
     * Atualizar => Método responsável por levar a tela de alteração de um registro 
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
     * Método responsável por gerar o método alterar
     * Alterar => Método responsável por receber o request com o registro e alterar no banco 
     */
    private function defaultMethodAlterar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append(
            "// metodo alterar"
        );
        return Helpers::createMethod("alterar", "\$request", $oBody);
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
     * Método responsável por gerar o método listar
     * Listar => Método responsável por levar a tela de visualização com todos os registros 
     */
    private function defaultMethodListar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("\$" . $sName . "BO = new " . ucfirst($sName) . "BO(new " . ucfirst($sName) . "DAO());\n")
            ->appendNL("\$this->view->many" . ucfirst($sName) . " = \$" . $sName . "BO->buscarTodos();\n")
            ->appendNL("\$this->requisitarView('" . $sName . "/listar', 'baseHtml');");
        
        return Helpers::createMethod("listar", null, $oBody);
    }
    
    /**
     * Método responsável por gerar o método deletar
     * Deletar => Método responsável por receber o id do registro e excluir do banco 
     */
    private function defaultMethodDeletar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("\$" . $sName . "BO = new " . ucfirst($sName) . "BO(new " . ucfirst($sName) . "DAO());\n")
            ->appendNL("\$result = \$" . $sName . "BO->buscarUm(\$id);")
            ->appendNL("if (empty(\$result)) {")
            ->appendNL("Redirecionador::paraARota('" . $sName . "/listar');")
            ->appendNL("return;")
            ->appendNL("}")
            ->appendNL("\$this->view->" . $sName . " = \$result;")
            ->appendNL("\$this->requisitarView('" . $sName . "/visualizar', 'baseHtml');");

        return Helpers::createMethod("deletar", "\$id", $oBody);
    }
}