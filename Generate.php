<?php

require_once('generator/GenerateAutoload.php');
require_once('generator/GenerateFolders.php');

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
    }

    /**
     * Método que realiza a leitura do arquivo json
     */
    private function getFile()
    {
        return json_decode(file_get_contents(self::FILENAME));
    }
}

    
//     // CRIACAO DA CONEXAO
//     $conexao = 
// '<?php

// class Conexao {

//     private const DB_TYPE = "' . $json->pdo->driver . '";
//     private const DB_HOST = "' . $json->pdo->host . '";
//     private const DB_NAME = "' . $json->pdo->name . '";
//     private const DB_USER = "' . $json->pdo->user . '";
//     private const DB_PASSWORD = ' . ($json->pdo->password != "" ? '"'.$json->pdo->password.'"' : '""') . ';

//     // instance
//     private static $conexao;

//     // getInstance
//     public static function startConnection()
//     {
//         if (isset(self::$conexao))
//             return self::$conexao;
        
//         try {
//             self::$conexao = new PDO(self::DB_TYPE . ":host=" . self::DB_HOST . ";dbname=" . self::DB_NAME, self::DB_USER, self::DB_PASSWORD);
//             self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             return self::$conexao;
            
//         } catch(PDOException $e) {
//             return "Error: " . $e->getMessage();
//         }
//     }
// }';

//     if (!file_exists(__DIR__ . '/conexao'))
//         mkdir(__DIR__ . '/conexao', 0777, true);
//     $fp = fopen('conexao/Conexao.php', 'w');
//     fwrite($fp, $conexao);
//     fclose($fp);

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
