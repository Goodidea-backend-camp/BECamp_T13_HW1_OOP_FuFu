<?php

/* 
資料庫實體化：
1. 負責確認玩家和敵人是否已經存在
2. 如果不存在則提示玩家創建或返回主菜單
3. 根據角色或敵人的資料，顯示角色或敵人的資訊。
*/

namespace PlayGame;

require_once 'Database.php';
require_once 'View.php';
require_once 'Character.php';

class EntityChecker
{
    use Character;    
    private $name; 
    private $levelName; 
    private $level = 1; 
    private $experience; 

    public function __construct()
    {
        $this->character();  
        $this->database = new Database();
    }

    // 確認玩家是否建立，沒有建立就要先建立玩家
    public function existPlayer(): void
    {

        try {
            $this->name = readline('請輸入您要使用的角色名稱：');
            $playerData = $this->database->getPlayerName($this->name);

            if ($playerData) {
                // 如果找到角色，設置角色屬性
                $this->name = $playerData['character_name'];
                $this->healthPoints = $playerData['character_healthPoints'];
                $this->physicalAttack = $playerData['character_physicalAttack'];
                $this->magicAttack = $playerData['character_magicAttack'];
                $this->physicalDefense = $playerData['character_physicalDefense'];
                $this->magicDefense = $playerData['character_magicDefense'];
                $this->magicPoints = $playerData['character_magicPoints'];
                $this->luckiness = $playerData['character_luckiness'];
                $this->level = $playerData['character_level'] ?? 1;
                $this->experience = $playerData['character_experience'] ?? 0;
                $this->displayPlayer();
            } else {
                echo "需要重新建立角色\n";
                View::returnToMainMenu();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function displayPlayer(): void
    {
        // 顯示敵人資訊
        echo "已找到角色，開始遊戲\n";
        echo "角色名稱: " . $this->name . "\n";
        echo "角色生命: " . $this->healthPoints . "\n";
        echo "物理攻擊: " . $this->physicalAttack . "\n";
        echo "魔法攻擊: " . $this->magicAttack . "\n";
        echo "物理防禦: " . $this->physicalDefense . "\n";
        echo "魔法防禦: " . $this->magicDefense . "\n";
        echo "魔力值：" . $this->magicPoints . "\n";
        echo "幸運值：" . $this->luckiness . "\n";
    }


    // 確認敵人是否存在，找不到就是關卡結束
    public function existEnemy(): void
    {
        try {
            $enemyData = $this->database->getEnemyData($this->level);
            if ($enemyData) {
                $this->level = $enemyData['enemy_id'];
                $this->levelName = $enemyData['enemy_level_name'];
                $this->name = $enemyData['enemy_name'];
                $this->healthPoints = $enemyData['enemy_healthPoints'];
                $this->physicalAttack = $enemyData['enemy_physicalAttack'];
                $this->magicAttack = $enemyData['enemy_magicAttack'];
                $this->physicalDefense = $enemyData['enemy_physicalDefense'];
                $this->magicDefense = $enemyData['enemy_magicDefense'];
                $this->displayEnemy();
            } else {
                // 如果未找到敵人，10關結束
                echo "🏆 對戰遊戲結束，恭喜玩家勝利 🏆\n";
                View::returnToMainMenu();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function displayEnemy(): void
    {
        // 顯示敵人資訊
        echo "══════════════════\n";
        echo " 歡迎來到第 {$this->level} 關 {$this->levelName}\n";
        echo "══════════════════\n";
        echo "你遇到了一個：" . $this->name . "\n";
        echo "生命值：" . $this->healthPoints . "\n";
        echo "物理攻擊：" . $this->physicalAttack . "\n";
        echo "魔法攻擊：" . $this->magicAttack . "\n";
        echo "物理防禦：" . $this->physicalDefense . "\n";
        echo "魔法防禦：" . $this->magicDefense . "\n\n";
    }
}
