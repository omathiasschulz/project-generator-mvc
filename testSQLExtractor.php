<?php

require_once('SQLExtractor.php');

$aDatabase = SQLExtractor::getSQLData('sql.sql');

echo '<pre>' , var_dump($aDatabase) , '</pre>';
