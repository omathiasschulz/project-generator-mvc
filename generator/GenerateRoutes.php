<?php

namespace generator;

use helpers\Helpers;

class GenerateRoutes
{
    /**
     * Método responsável por gerar as rotas
     */
    public static function create($aTabelas)
    {
        $routes = "<?php\n";
        foreach ($aTabelas as $key => $oTabela) {
            $routes .= self::defaultRoutes($oTabela->nome);
        }
        $routes .= "\nreturn \$route;\n";

        Helpers::createFolder('app');
        Helpers::writeFile('app/routes.php', $routes);
    }

    /**
     * Método responsável por gerar as rotas padrões de cada controller
     */
    private function defaultRoutes($routeName)
    {
        $routes = ""
            . "\n\$route[] = ['/" . $routeName . "/cadastrar', '" . ucfirst($routeName) . "Controller@cadastrar'];"
            . "\n\$route[] = ['/" . $routeName . "/inserir', '" . ucfirst($routeName) . "Controller@inserir'];"
            . "\n\$route[] = ['/" . $routeName . "/{id}/visualizar', '" . ucfirst($routeName) . "Controller@visualizar'];"
            . "\n\$route[] = ['/" . $routeName . "/listar', '" . ucfirst($routeName) . "Controller@listar'];"
            . "\n\$route[] = ['/" . $routeName . "/{id}/atualizar', '" . ucfirst($routeName) . "Controller@atualizar'];"
            . "\n\$route[] = ['/" . $routeName . "/{id}/deletar', '" . ucfirst($routeName) . "Controller@deletar'];\n";
        return $routes;
    }
}
