<?php

namespace core;

class Redirecionador
{

    public static function paraARota($rota)
    {
        header("Location: $rota");
    }
    
}