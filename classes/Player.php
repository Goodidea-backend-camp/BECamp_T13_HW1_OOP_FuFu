<?php

/* 
玩家建立角色，需要功能：
1. 玩家姓名
2. 從資料庫選取職業
3. 自由配點能力值
4. 合併職業能力值與自由配點能力值
5. 存入資料庫
*/

namespace PlayGame;

require_once 'Database.php';
require_once 'Character.php';

class Player
{
    use Character;
    private $roleName;

    // 初始化 Player 對象並設置其屬性。
    public function __construct()
    {
        $this->character();
        $this->database = new Database();
        echo "\n";
        $name = readline("請輸入玩家姓名：");
        echo "\n";
        $this->selectRole($name)->creatPoint();
        $this->saveToDatabase($name);
    }

    // 從資料庫選取職業(印出所有職業->選取職業)
    public function selectRole(string $playerName): self
    {
        echo $this->database->getAllRoles($playerName);
        $userChoice = readline("請填入1/2/3/4選擇職業：");

        try {
            // 檢查用戶選擇的角色ID是否有效
            if ($this->database->isRoleIdValid($userChoice)) {
                $roleStats = $this->database->getRoleId($userChoice);

                $this->roleName = $roleStats["role_name"];
                $this->physicalAttack = $roleStats["role_physicalAttack"];
                $this->magicAttack = $roleStats["role_magicAttack"];
                $this->physicalDefense = $roleStats["role_physicalDefense"];
                $this->magicDefense = $roleStats["role_magicDefense"];

                echo "您選擇的角色是{$this->roleName}\n";
            } else {
                View::clearScreen();
                echo "無效的選擇！\n請重新選擇您喜歡的職業(1/2/3/4)\n";
                self::selectRole($playerName);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $this;
    }

    // 玩家自由配點能力值
    public function creatPoint(): void
    {
        $attributes = [
            'physicalAttack' => '物理攻擊力',
            'magicAttack' => '魔法攻擊力',
            'physicalDefense' => '物理防禦力',
            'magicDefense'  => '魔法防禦力',
            'magicPoints' => '魔力值',
            'luckiness' => '幸運值'
        ];
        // 初始化分配數值的陣列
        $attributeValues = array_fill_keys(array_keys($attributes), 0);
        $totalPoints = 10;

        echo "\n請分配10點能力值給" . implode('、', array_values($attributes)) . "\n";

        foreach ($attributes as $key => $attributeName) {
            if ($totalPoints <= 0) {
                break;
            }

            // 提示用戶輸入剩餘點數
            echo "{$attributeName}(剩餘{$totalPoints}點): ";

            // 獲取用戶輸入，並確保是有效的數字
            $input = (int)readline();
            while (!is_numeric($input) || $input < 0 || $input > $totalPoints) {
                echo "無效輸入，請重新輸入 \n{$attributeName}(剩餘{$totalPoints}點): ";
                $input = trim(fgets(STDIN));
            }

            // 更新分配值和剩餘點數
            $attributeValues[$key] = $input;
            $totalPoints -= $input;
        }

        // 更新玩家屬性
        $this->physicalAttack += $attributeValues['physicalAttack'];
        $this->magicAttack += $attributeValues['magicAttack'];
        $this->physicalDefense += $attributeValues['physicalDefense'];
        $this->magicDefense += $attributeValues['magicDefense'];
        $this->magicPoints += $attributeValues['magicPoints'];
        $this->luckiness += $attributeValues['luckiness'];
        echo "\n";
        echo "=== 完成能力值點數分配！===\n";
        echo "物理攻擊力：" . $this->physicalAttack . "\n";
        echo "魔法攻擊力：" . $this->magicAttack . "\n";
        echo "物理防禦力：" . $this->physicalDefense . "\n";
        echo "魔法防禦力：" . $this->magicDefense . "\n";
        echo "魔力值：" . $this->magicPoints . "\n";
        echo "幸運值：" . $this->luckiness . "\n";
    }

    // 玩家角色存入資料庫
    public function saveToDatabase(string $name): void
    {
        $this->database->saveCharacter([
            'character_name' => $name,
            'character_healthPoints' => $this->healthPoints,
            'character_physicalAttack' => $this->physicalAttack,
            'character_magicAttack' => $this->magicAttack,
            'character_physicalDefense' => $this->physicalDefense,
            'character_magicDefense' => $this->magicDefense,
            'character_magicPoints' => $this->magicPoints,
            'character_luckiness' => $this->luckiness,
            'character_role'=> $this->roleName,
        ]);
        echo "玩家角色已成功保存到資料庫！";
    }
}
