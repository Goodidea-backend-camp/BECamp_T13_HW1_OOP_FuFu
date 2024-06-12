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

//==============================================================================
//【玩家】=======================================================================
class EntityCheckerPlayer
{
    use Character;
    private $experience;
    public $level;
    private $player;
    private $playName;

    public function __construct()
    {
        $this->character();
        $this->database = new Database();
        $this->playName = readline('請輸入您要使用的角色是：');
    }

    // 確認玩家是否建立，沒有建立就要先建立玩家
    public function existPlayer()
    {
        try {

            $playerData = $this->database->getPlayerName($this->playName);
            if ($playerData) {
                $this->player = $this->getPlayerAttributes($playerData);
                $this->displayPlayer();
                return $this->player;
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
        echo "\n已找到角色，開始遊戲\n\n";
        echo "角色名稱: " . $this->name . "\n";
        echo "角色生命: " . $this->healthPoints . "\n";
        echo "物理攻擊: " . $this->physicalAttack . "\n";
        echo "魔法攻擊: " . $this->magicAttack . "\n";
        echo "物理防禦: " . $this->physicalDefense . "\n";
        echo "魔法防禦: " . $this->magicDefense . "\n";
        echo "魔力值：" . $this->magicPoints . "\n";
        echo "幸運值：" . $this->luckiness . "\n\n";
    }

    public function getPlayerAttributes($attributes): self
    {
        $this->name = $attributes['character_name'];
        $this->healthPoints = $attributes['character_healthPoints'];
        $this->physicalAttack = $attributes['character_physicalAttack'];
        $this->magicAttack = $attributes['character_magicAttack'];
        $this->physicalDefense = $attributes['character_physicalDefense'];
        $this->magicDefense = $attributes['character_magicDefense'];
        $this->magicPoints = $attributes['character_magicPoints'];
        $this->luckiness = $attributes['character_luckiness'];
        $this->level = $attributes['character_level'] ?? 1;
        $this->experience = $attributes['character_experience'] ?? 0;

        return $this;
    }
}


//==============================================================================
//【敵人】=======================================================================
class EntityCheckerEnemy
{
    use Character;
    private $levelName;
    public $level;
    private $enemy;

    public function __construct($level)
    {
        $this->character();
        $this->database = new Database();
        $this->level = $level;
    }

    // 確認敵人是否存在，找不到就是關卡結束
    public function existEnemy()
    {

        $enemyData = $this->database->getEnemyData($this->level);
        if ($enemyData) {
            $this->enemy = $this->getEnemyAttributes($enemyData);
            $this->displayEnemy();
            return $this->enemy;
        } else {
            // 如果未找到敵人，10關結束
            echo "🏆 對戰遊戲結束，恭喜玩家勝利 🏆\n";
            return false;
        }
    }

    private function displayEnemy(): void
    {
        // 顯示敵人資訊
        echo "══════════════════════════════\n";
        echo " 歡迎來到第 {$this->level} 關 《{$this->levelName}》\n";
        echo "══════════════════════════════\n";
        echo "你遇到了一個：" . $this->name . "\n";
        echo "生命值：" . $this->healthPoints . "\n";
        echo "物理攻擊：" . $this->physicalAttack . "\n";
        echo "魔法攻擊：" . $this->magicAttack . "\n";
        echo "物理防禦：" . $this->physicalDefense . "\n";
        echo "魔法防禦：" . $this->magicDefense . "\n\n";
    }

    public function getEnemyAttributes($attributes): self
    {
        $this->name = $attributes['enemy_name'];
        $this->levelName = $attributes['enemy_level_name'];
        $this->level = $attributes['enemy_id'];
        $this->healthPoints = $attributes['enemy_healthPoints'];
        $this->physicalAttack = $attributes['enemy_physicalAttack'];
        $this->magicAttack = $attributes['enemy_magicAttack'];
        $this->physicalDefense = $attributes['enemy_physicalDefense'];
        $this->magicDefense = $attributes['enemy_magicDefense'];

        return $this;
    }
}
