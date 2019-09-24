<?php

require_once('generator/Generate.php');

$result = Generate::start();

echo $result[1];
