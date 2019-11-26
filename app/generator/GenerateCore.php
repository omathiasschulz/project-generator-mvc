<?php

namespace generator;

class GenerateCore 
{
    /**
     * Método responsável por gerar a classe core
     */
    public function create()
    {
        exec("cd Project/ && git clone https://github.com/mathiasarturschulz/core.git");
    }
}