<?php

namespace PlayGame;

require_once 'Character.php';
require_once 'EntityChecker.php';

class BattleRound
{
    use Character;

    private $player;
    private $enemy;
    private $experience;

    public function __construct($player, $enemy)
    {
        $this->player = $player;
        $this->enemy = $enemy;
    }

    public function isBattleOngoing(): bool
    {
        return $this->player->healthPoints > 0 && $this->enemy->healthPoints > 0;
    }

    public function isEnemyDefeated(): bool
    {
        return $this->enemy->healthPoints <= 0;
    }
    public function isPlayerDefeated(): bool
    {
        return $this->player->healthPoints <= 0;
    }


    // 玩家選擇攻擊方式
    public function playerTurn()
    {
        echo "◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇\n";
        $chooseAttack = readline("請選擇您要的攻擊方式 (1).物理攻擊 (2).魔法攻擊：");
        echo "\n";

        if ($chooseAttack == "1") {
            echo "您選擇[🪓 物理攻擊]\n";
            $this->attack($this->player, $this->enemy, 'physical');
        } elseif ($chooseAttack == "2") {
            if ($this->player->magicPoints > 0) {
                echo "您選擇[🪄 魔法攻擊]\n";
                $this->player->magicPoints -= 1;
                $this->attack($this->player, $this->enemy, 'magic');
                echo "剩餘 🔮 魔力值：{$this->player->magicPoints}\n\n";
            } else {
                echo "🔮 魔力值不足，無法使用魔法攻擊\n\n";
                self::playerTurn();
            }
        } else {
            echo '輸入無效，請重新入';
            self::playerTurn();
        }
    }

    // 敵人隨機攻擊方式
    public function enemyTurn()
    {
        echo "◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇\n";
        $randAttack = rand(1, 2);
        if ($randAttack == "1") {
            echo "敵人使用[🪓 物理攻擊]\n";
            $this->attack($this->enemy, $this->player, 'physical');
        } elseif ($randAttack == "2") {
            echo "敵人使用[🪄 魔法攻擊]\n";
            $this->attack($this->enemy, $this->player, 'magic');
        }
    }

    // 玩家升級處理
    public function playerVictory(): void
    {
        $this->player->healthPoints = round($this->player->healthPoints * 1.2);
        $this->player->physicalAttack = round($this->player->physicalAttack * 1.2);
        $this->player->physicalDefense = round($this->player->physicalDefense * 1.2);
        $this->player->magicAttack = round($this->player->magicAttack * 1.2);
        $this->player->magicDefense = round($this->player->magicDefense * 1.2);
        $this->player->magicPoints = round($this->player->magicPoints * 1.2);
        $this->player->level += 1;
        $this->experience += 100;

        echo "升級成功！\n";
        echo "新等級：{$this->player->level}\n";
        echo "新的生命值：{$this->player->healthPoints}\n";
        echo "新的物理攻擊：{$this->player->physicalAttack}\n";
        echo "新的魔法攻擊：{$this->player->magicAttack}\n";
        echo "新的物理防禦：{$this->player->physicalDefense}\n";
        echo "新的魔法防禦：{$this->player->magicDefense}\n\n";
        readline("點選enter繼續遊戲...\n\n");
        echo "\033[2J\033[0;0H";
    }


    // 產生隨機數，使用幸運值
    private function randNumber(): int
    {
        $randNumber = rand(1, 8);
        return $randNumber <= $this->player->luckiness;
    }

    // 攻擊方式
    private function attack($attacker, $defender, $type): void
    {
        // 處理物理攻擊
        if ($type == 'physical') {
            if ($attacker === $this->enemy && $this->randNumber()) {
                
                // 使用幸運值增加防禦能力
                $damage = $attacker->physicalAttack - ($defender->physicalDefense * 3);
                echo "{$defender->name} 使用 🎲 幸運值{$this->luckiness}，提升防禦能力\n";
            } else {
                $damage = $attacker->physicalAttack - $defender->physicalDefense;
            }

            // 處理魔法攻擊
        } elseif ($type == 'magic') {            
            $damage = $attacker->magicAttack - $defender->magicDefense;
        }

        $damage = round(max(0, $damage)); // 確保傷害值不為負
        $defender->healthPoints -= $damage;
        $defender->healthPoints = round(max(0, $defender->healthPoints));
        echo "{$attacker->name} 對 {$defender->name} 造成了 {$damage} 點傷害！\n";
        echo "{$defender->name} 剩餘生命值：{$defender->healthPoints}\n\n";
    }
}
