<?php

require_once('generator/GenerateAutoload.php');
require_once('generator/GenerateFolders.php');
require_once('generator/GenerateConexao.php');

class Generate
{
    const FILENAME = 'autoload.json';

    /**
     * Método principal
     */
    public static function start()
    {
        if (!file_exists(self::FILENAME))
            return [false, 'Arquivo ' . self::FILENAME . ' não existe!'];
        $json = self::getFile();

        GenerateAutoload::create($json->folders, ['conexao']);
        GenerateFolders::create($json->folders);
        GenerateConexao::create($json->pdo);
    }

    /**
     * Método que realiza a leitura do arquivo json
     */
    private function getFile()
    {
        return json_decode(file_get_contents(self::FILENAME));
    }
}

//     // CRIACAO DO TESTE DE CONEXAO
//     $index = 
// '<?php

// require_once "autoload.php";

// if (Conexao::startConnection())
//     echo "Conexão efetuada com sucesso!";
// else
//     echo "Erro ao conectar ao banco!";

// ';
//     $fp = fopen('index.php', 'w');
//     fwrite($fp, $index);
//     fclose($fp);
    
// } else {
//     echo "O arquivo $filename não existe";
// }
