<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateCore 
{
    /**
     * Método responsável por gerar a classe core
     */
    public function create()
    {
        \exec("cd ../");
        \exec("git clone https://github.com/mathiasarturschulz/core.git");
    }
}