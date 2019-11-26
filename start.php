<?php

require_once "vendor/autoload.php";

use generator\Generate;

$result = Generate::start();

echo $result[1];
