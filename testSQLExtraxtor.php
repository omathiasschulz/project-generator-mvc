<?php

require_once('SQLExtractor.php');

$aDatabase = SQLExtractor::getSQLData('sql.sql');

echo '<pre>' , var_dump($aDatabase) , '</pre>';


echo $aDatabase['nome'];

echo '<pre>' , var_dump($aDatabase['tabelas'][0]) , '</pre>';