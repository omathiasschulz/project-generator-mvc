<?php

namespace helpers;

class SQLExtractor
{
    // Regex que pega tudo até o primeiro ponto e vírgula
    const PATTERN_TO_THE_SEMICOLON = "/(?i)\A([^;]+)\;/";

    const TYPES_NORMAL = ['decimal', 'tinyint', 'int', 'bigint', 'float', 'double', 'date', 'time', 'datetime', 'year'];
    const TYPES_WITH_CONFIG_ONE = ['varchar', 'decimal'];
    const TYPES_WITH_CONFIG_TWO = ['decimal'];

    /**
     * Método principal que retornará o nome do banco, as tabelas e os atributos de cada tabela
     * Retorna um json, no qual a primeira posição determina se a operação foi realizada 
     * com sucesso ou não, a segunda posição é a resposta
     */
    public static function getSQLData($sSqlName)
    {
        $sSql = self::getSQLFromFile($sSqlName);

        $aDatabaseName = self::getDatabaseName($sSql);
        if (!$aDatabaseName[0])
            return json_encode($aDatabaseName);
        $sSql = self::splitToTheSemicolon($sSql);

        $aTables = self::getTables($sSql);
        if (!$aTables[0])
            return json_encode($aTables);
        
        $aFormattedDatabase = [
            'nome'    => $aDatabaseName[1],
            'tabelas' => $aTables[1]
        ];
        
        return json_encode([true, $aFormattedDatabase]);
    }
    
    /**
     * Método responsável por buscar a SQL a partir do arquivo especificado
     */
    private function getSQLFromFile($sSqlName)
    {
        $sCompletePath = __DIR__ . DIRECTORY_SEPARATOR . $sSqlName;
        if (file_exists($sCompletePath))
            return file_get_contents($sCompletePath);
        return false;
    }

    /**
     * Método responsável por buscar o nome do database
     */
    private function getDatabaseName($sSql)
    {
        $sPattern = "/(?i)^[[:space:]]*create[[:space:]]+database[[:space:]]+([a-zA-Z0-9]\w+)[[:space:]]*;/";
        preg_match_all($sPattern, $sSql, $aMatches);
        return isset($aMatches[1][0]) 
            ? [true, $aMatches[1][0]] 
            : [false, 'Erro ao buscar o nome do Database'];
    }

    /**
     * Método responsável por buscar as tabelas do database
     */
    private function getTables($sSql)
    {
        $sTypes = self::generateTypeAttributes();
        $sTypes = self::generateTypeNotNull($sTypes);

        // Loop que passará por todas as tabelas do SQL
        while(!empty($sSql)) {
            // Pega o nome e os atributos da tabela atual
            $sPattern = "/(?i)\A[[:space:]]*create[[:space:]]+table[[:space:]]+([a-zA-Z0-9]\w+)[[:space:]]*(\([^;]+)\;/";
            preg_match_all($sPattern, $sSql, $aMatches);
            $sTableName = isset($aMatches[1][0]) ? $aMatches[1][0] : null;
            $sTableAttributes = isset($aMatches[2][0]) ? $aMatches[2][0] : null;
            
            if (empty($sTableName))
                return [false, 'Erro ao buscar o nome de uma das tabelas'];
            
            $aActualAttributes = self::getTableAttributes($sTableAttributes, $sTypes);
            if (!$aActualAttributes[0])
                return $aActualAttributes;

            $aFormattedTables[] = [
                'nome'      => $sTableName,
                'atributos' => $aActualAttributes[1]
            ];

            // Remove a tabela atual do SQL
            $sSql = self::splitToTheSemicolon($sSql);
        }

        return [true, $aFormattedTables];
    }

    /**
     * Método responsável por retornar um array com os atributos passados na string
     */
    private function getTableAttributes($sAttributes, $sTypes)
    {
        // Remove os parênteses iniciais e finais
        $sAttributes = substr(trim($sAttributes), 1, strlen($sAttributes) - 2);

        // Verifica as chaves primárias e já retira dos parâmetros também
        $aPrimaryKeys = self::getTablePrimaryKey($sAttributes);
        if (!$aPrimaryKeys[0])
            return $aPrimaryKeys;
        $aFormattedAttributes[] = ['chaves_primarias' => $aPrimaryKeys[1]];
        $sPattern = "/(?i)\,[[:space:]]*primary[[:space:]]+key\(([a-zA-Z0-9\_\-\,[:space:]]+)\)$/";
        $sAttributes = preg_split($sPattern, $sAttributes)[0];

        // Realiza um split na vírgula 
        // Entretanto não pode dar split na virgula de atributos como decimal(3,3)
        $aAttributes = preg_split("/(?<=[^0-9])\,/", $sAttributes);
        
        foreach ($aAttributes as $sAttribute) {
            $aActualAttribute = self::getTableOneAttribute($sAttribute, $sTypes);
            if (!$aActualAttribute[0])
                return $aActualAttribute;
            $aFormattedAttributes[] = $aActualAttribute[1];
        }
        return [true, $aFormattedAttributes];
    }
    
    /**
     * Método responsável por pegar as chaves primárias da tabela
     */
    private function getTablePrimaryKey($sAttribute)
    {
        $sAttribute = trim($sAttribute);
        $sPattern = "/(?i)\,[[:space:]]*primary[[:space:]]+key\(([a-zA-Z0-9\_\-\,[:space:]]+)\)$/";
        preg_match_all($sPattern, $sAttribute, $aMatches);

        if (!isset($aMatches[1][0])) {
            return [false, 'Erro ao buscar as chaves primárias de uma das tabelas'];
        }
        $aChaves = explode(',', $aMatches[1][0]);
        // Remove espaços em branco antes e depois de cada chave primária
        foreach ($aChaves as $key => $sChave)
            $aChaves[$key] = trim($sChave);

        return [true, $aChaves];
    }

    /**
     * Método responsável por pegar e validar um atributo da tabela
     */
    private function getTableOneAttribute($sAttribute, $sTypes)
    {
        $sAttribute = trim($sAttribute);
        $sPattern = "/(?i)^([a-zA-Z0-9_-]+)[[:space:]]+" . $sTypes . "(auto_increment[[:space:]]*|)$/";
        preg_match_all($sPattern, $sAttribute, $aMatches);

        if (!isset($aMatches[1][0]) || !isset($aMatches[2][0]))
            return [false, 'Erro ao buscar os atributos de uma tabela'];

        return [
            true,
            [
                'nome'     => $aMatches[1][0],
                'tipo'     => $aMatches[2][0],
                'not_null' => !empty($aMatches[9][0]) ? true : false
            ]
        ];
    }

    /**
     * Método responsável por limpar o SQL até o primeiro ponto e vírgula encontrado
     */
    private function splitToTheSemicolon($sSql)
    {
        return preg_split(self::PATTERN_TO_THE_SEMICOLON, $sSql)[1];
    }

    /**
     * Método responsável por montar a string em regex com os tipos de variáveis
     * permitidos especificadas nas constantes:
     * TYPES_NORMAL          => Tipo de variável normal, ex: int
     * TYPES_WITH_CONFIG_ONE => Tipo de variável que aceita um parâmetro de configuração, ex: varchar(50)
     * TYPES_WITH_CONFIG_TWO => Tipo de variável que aceita dois parâmetros de configuração, ex: decimal(2,3)
     */
    private function generateTypeAttributes()
    {
        $sTypesNormal = implode('|', self::TYPES_NORMAL);
        $sTypesConfigOne = implode('|', self::TYPES_WITH_CONFIG_ONE);
        $sTypesConfigTwo = implode('|', self::TYPES_WITH_CONFIG_TWO);

        return '('
            // variavel
            . '((' . $sTypesNormal . '))'
            // variavel (parametro01)
            . '|((' . $sTypesConfigOne . ')[[:space:]]*\([[:space:]]*[0-9]+[[:space:]]*\))'
            // variavel (parametro01, parametro02)
            . '|((' . $sTypesConfigTwo . ')[[:space:]]*\([[:space:]]*[0-9]+[[:space:]]*\,[[:space:]]*[0-9]+[[:space:]]*\))'
            . ')';
    }

    /**
     * Método responsável por gerar a configuração not null para os tipos
     * passados como parâmetro
     * Retorna um regex com a nova configuração
     */
    private function generateTypeNotNull($sTypes)
    {
        return $sTypes . '([[:space:]]*|[[:space:]]+not[[:space:]]+null[[:space:]]*)';
    }
}