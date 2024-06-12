<?php

/* 
遊戲對戰，需要功能：
1. 確認是否有角色資料 
2. 取敵人資料
3. 遊戲開始 (玩家生命值>0 && 敵人生命值>0)
    a. 設定遊戲開始的時間 $startTime
    b. 玩家攻擊方式(物理攻擊◎幸運值；魔法攻擊◎魔力值)
    c. 敵人攻擊方式(物理攻擊;魔法攻擊)
    d. 創建攻擊function
4. 進入下一關 (玩家生命值>0 && 敵人生命值<0)
    a. 恢復生命值、魔力值
    b. 玩家升等方式
5. 遊戲結束 (玩家生命值 <0 && 找不到敵人)
    a. 設定遊戲結束時間 $endTime 
6. 儲存遊戲紀錄
*/

namespace PlayGame;


require_once 'Database.php';
require_once 'BattleRound.php';
require_once 'GameRecord.php';

class Fight
{

    private $player;
    private $enemy;
    private $battleRound;
    private $gameRecord;
    private $level;

    public function __construct()
    {
        $this->level = 1;
        $this->initializeEntities($this->level); // 初始化角色與敵人
        $this->battleRound = new BattleRound($this->player, $this->enemy);
        $this->gameRecord = new GameRecord($this->player);
        $this->start();
    }

    // 初始化角色與敵人，只在需要時調用
    private function initializeEntities($level): void
    {
        $entityCheckerPlayer = new EntityCheckerPlayer();
        $this->player = $entityCheckerPlayer->existPlayer(); // 取得玩家對象

        $entityCheckerEnemy = new EntityCheckerEnemy($level);
        $this->enemy = $entityCheckerEnemy->existEnemy(); // 取得敵人對象
    }

    // 開始遊戲
    public function start(): void
    {
        $initialHealthPoints = $this->player->healthPoints; // 恢復玩家生命值
        $initialMagicPoints = $this->player->magicPoints; // 恢復玩家魔力值
        
        $this->gameRecord->start(); // 開始遊戲計時

        while ($this->battleRound->isBattleOngoing()) {
            $this->battleRound->playerTurn();

            // 敵人被擊敗
            if ($this->battleRound->isEnemyDefeated()) {

                // 恢復生命值與魔力值
                $this->player->healthPoints = $initialHealthPoints;
                $this->player->magicPoints = $initialMagicPoints;

                // 玩家勝利處理
                $this->battleRound->playerVictory();
                
                // 升級重新初始化敵人
                $this->level++;
                $entityCheckerEnemy = new EntityCheckerEnemy($this->level);
                $this->enemy = $entityCheckerEnemy->existEnemy();
                $this->battleRound = new BattleRound($this->player, $this->enemy);

                // 沒有敵人，遊戲結束
                if ($this->enemy === false) {
                    $this->gameRecord->gameOver();
                    break;
                }
            }

            // 玩家被擊敗，遊戲結束
            if ($this->battleRound->isPlayerDefeated()) {
                $this->gameRecord->gameOver(); // 遊戲結束處理
                break;
            }
            $this->battleRound->enemyTurn();
        }

        $this->gameRecord->gameEnd();
    }
}
