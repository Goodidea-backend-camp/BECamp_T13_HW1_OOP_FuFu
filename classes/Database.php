<?php

namespace Playgame;

use PDO;
use PDOException;
use Dotenv\Dotenv;

// 連接資料庫
class Database
{
    private $pdo;

    public function __construct()
    {
        // 引入 .env 檔案中的環境變數
        require_once __DIR__ . '/../vendor/autoload.php';
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');

        $dotenv->load();

        $host = getenv('DB_HOST',true);
        $dbname = getenv('DB_DATABASE',true);
        $dbusername = getenv('DB_USERNAME',true);
        $dbpassword = getenv('DB_PASSWORD',true);

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "資料庫連線成功\n";
            sleep(1);
        } catch (PDOException $e) {
            die("資料庫連線失敗：" . $e->getMessage()."\n");
            
        }
    }
}

$database = new Database();
/*
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$dbusername = $_ENV['DB_USERNAME'];
$dbpassword = $_ENV['DB_PASSWORD'];
*/