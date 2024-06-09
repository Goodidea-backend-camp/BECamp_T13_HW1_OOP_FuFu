<?php

/* 要使用資料庫的資料表與功能：
【職業資料表】
    a. 取單筆資料
    b. 獲取所有職業的詳細資料
    c. 檢查用戶的職業選擇是否有效
【玩家資料表】
    a. 將創建好的角色存入資料庫
【敵人資料表】
    a. 獲取單筆敵人資料
    b.
【遊戲紀錄】
    a. 
    b.


*/

namespace PlayGame;

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

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_DATABASE'];
        $dbusername = $_ENV['DB_USERNAME'];
        $dbpassword = $_ENV['DB_PASSWORD'];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("資料庫連線失敗：" . $e->getMessage() . "\n");
        }
    }

    #region 【 Role 職業相關 】 ======================================================================

    public function getRoleId(int $roleId)
    {
        $roleData = $this->fetch("SELECT * FROM `Role` WHERE role_id = :role_id", [':role_id' => $roleId]);

        // 如果找不到指定職業的資料，則拋出異常
        if (!$roleData) {
            throw new \Exception("找不到角色ID為 $roleId 的角色資料");
        }

        // 返回職業詳細資料
        return $roleData;
    }

    // 獲取所有職業的詳細資料
    public function getAllRoles(string $name)
    {
        // 從資料庫中獲取所有職業的詳細資料
        $roles = $this->fetchAll("SELECT role_id, role_name, role_physicalAttack, role_magicAttack, role_physicalDefense, role_magicDefense FROM `Role`");

        $output = "=== {$name} ，您好，請選擇喜歡的職業 ===\n";

        // 將每個職業的詳細資料格式化後添加到輸出字符串中
        foreach ($roles as $role) {
            $output .= "{$role['role_id']} => \"{$role['role_name']} (物理攻擊 = {$role['role_physicalAttack']} ; 魔法攻擊 = {$role['role_magicAttack']} ; 物理防禦 = {$role['role_physicalDefense']} ; 魔法防禦 = {$role['role_magicDefense']} )\"\n";
        }
        return $output; // 返回格式化後的職業資訊字符串
    }

    // 檢查用戶的職業選擇是否有效
    public function isRoleIdValid(int $roleId)
    {
        $roles = $this->fetchAll("SELECT role_id FROM `Role`");
        $roleIds = array_column($roles, 'role_id');

        return in_array($roleId, $roleIds);
    }

    #endregion

    #region 【 Player 語法相關 】 ======================================================================

    // 將創建好的角色存入資料庫

    public function saveCharacter($characterData)
    {
        $sql = "INSERT INTO `character` 
                   (character_name, character_healthPoints, character_physicalAttack, character_magicAttack, character_physicalDefense, character_magicDefense, character_magicPoints, character_luckiness) 
                   VALUES (:character_name, :character_healthPoints, :character_physicalAttack, :character_magicAttack, :character_physicalDefense, :character_magicDefense, :character_magicPoints, :character_luckiness)";
        $this->query($sql, $characterData);
    }

    // 取角色資料


    public function getPlayerName(string $character_name)
    {
        $playerData = $this->fetch("SELECT * FROM `character` WHERE `character_name` = :character_name", [":character_name" => $character_name]);
        return $playerData;
    }

    #endregion

    #region 【 Enemy 語法相關 】 ======================================================================

    public function getEnemyData(int $enemy_id)
    {
        $enemyData = $this->fetch("SELECT * FROM `enemy` WHERE  enemy_id = :enemy_id", [":enemy_id" => $enemy_id]);

        // 如果找不到敵人，則拋出異常
        if (!$enemyData) {
            throw new \Exception("找不到角色ID為 $enemy_id 的角色資料");
        }

        // 返回敵人詳細資料
        return $enemyData;
    }
    #endregion

    #region 【 Record 遊戲紀錄相關 】 ======================================================================

    // 保存遊戲紀錄
    public function saveRecord($character_name, $level, $startTime, $endTime)
    {
        try {
            $sql = "INSERT INTO `player` (player_name, player_levels, start_time, end_time) 
                    VALUES (:player_name, :player_levels, :start_time, :end_time)";
            $params = [
                ':player_name' => $character_name,
                ':player_levels' => $level,
                ':start_time' => $startTime,
                ':end_time' => $endTime
            ];
            $result = $this->query($sql, $params);

            if ($result) {
                echo "△ 遊戲紀錄已成功保存到資料庫 △\n";
                return $result;
            } else {
                echo "☠️ 遊戲紀錄保存失敗 ☠️\n";
                return null;
            }
        } catch (PDOException $e) {
            echo '連線失敗: ' . $e->getMessage() . "\n";
        }
    }

    // 取得遊戲紀錄
    public function getRecord($character_name)
    {
        $query = "SELECT player_name, player_levels, start_time, end_time 
                  FROM `player` 
                  WHERE player_name = :player_name
                  ORDER BY start_time DESC";
        $result = $this->fetchAll($query, [":player_name" => $character_name]);
        return $result ?: null;
    }

    #endregion

    #region 【 SQL 語法相關 】 ======================================================================

    // 執行查詢語法
    public function query($sql, $params = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    // 取得單筆資料
    public function fetch($sql, $params = [])
    {
        $statement = $this->query($sql, $params);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // 取得多筆資料
    public function fetchAll($sql, $params = [])
    {
        $statement = $this->query($sql, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    #endregion
}
