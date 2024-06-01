<?php

namespace PlayGame;

require_once 'Player.php'; // 創建角色

class View
{

    public function __construct()
    {
    }

    public static function mainScreen()
    {
        $Player = new Player();

        echo "(1).創建角色\n(2).開始遊戲\n(3).遊戲紀錄\n(4).結束遊戲\n";
        $selectAction = readline('請選擇:');
        if ($selectAction == '1') {
            // 創建角色
            $player = new Player();
            echo"\n";
            $playerName = readline("請輸入玩家姓名：");
            echo"\n";
            $player->selectRole($playerName)->creatPoint();
            $player->saveToDatabase($playerName);

            echo "玩家角色已成功保存到資料庫！\n";
        } elseif ($selectAction == '2') {
            // 開始遊戲


        } elseif ($selectAction == '3') {
            // 遊戲紀錄


        } elseif ($selectAction == '4') {
            // 結束遊戲
            echo '遊戲結束\n';
            exit();
        } else {
            echo "輸入無效，請重新選擇(1/2/3/4)" . "\n";
            self::returnToMainMenu();
        }
    }

    public static function returnToMainMenu()
    {
        readline("按 Enter 回到主選單...");
        self::clearScreen();
        self::mainScreen();
    }

    public static function clearScreen()
    {
        echo "\033[2J\033[0;0H";
    }
}

$View = new View();
View::mainScreen();
