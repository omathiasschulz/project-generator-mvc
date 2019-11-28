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
        $routes = "<?php\n"
            . "\n// Rota da página principal"
            . "\n\$route[] = ['/', 'HomeController@index'];\n"
        ;
        foreach ($aTabelas as $oTabela) {
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
            . "\n// Rota que leva a tela de cadastro um novo registro "
            . "\n\$route[] = ['/" . $routeName . "/cadastrar', '" . ucfirst($routeName) . "Controller@cadastrar'];"
            . "\n// Rota que recebe o request com o registro e insere no banco "
            . "\n\$route[] = ['/" . $routeName . "/inserir', '" . ucfirst($routeName) . "Controller@inserir'];"
            . "\n// Rota que leva a tela de alteração de um registro "
            . "\n\$route[] = ['/" . $routeName . "/{id}/atualizar', '" . ucfirst($routeName) . "Controller@atualizar'];"
            . "\n// Rota que recebe o request com o registro e altera no banco "
            . "\n\$route[] = ['/" . $routeName . "/alterar', '" . ucfirst($routeName) . "Controller@alterar'];"
            . "\n// Rota que leva a tela de visualização de um registro "
            . "\n\$route[] = ['/" . $routeName . "/{id}/visualizar', '" . ucfirst($routeName) . "Controller@visualizar'];"
            . "\n// Rota que leva a tela de visualização com todos os registros "
            . "\n\$route[] = ['/" . $routeName . "/listar', '" . ucfirst($routeName) . "Controller@listar'];"
            . "\n// Rota que recebe o id do registro e exclui do banco "
            . "\n\$route[] = ['/" . $routeName . "/{id}/deletar', '" . ucfirst($routeName) . "Controller@deletar'];\n";
        return $routes;
    }
}
