<?php

namespace PlayGame;

require_once 'Player.php'; // 創建角色
require_once 'Fight.php'; // 創建角色

class View
{

    public function __construct()
    {
    }

    public static function mainScreen()
    {
        echo "(1).創建角色\n(2).開始遊戲\n(3).遊戲紀錄\n(4).結束遊戲\n";
        $selectAction = readline('請選擇:');
        if ($selectAction == '1') {
            // 創建角色
            $player = new Player(); // 實例化 Player 物件
            self::returnToMainMenu();
        } elseif ($selectAction == '2') {
            // 開始遊戲
            $fight = new Fight();
            self::returnToMainMenu();
        } elseif ($selectAction == '3') {
            // 遊戲紀錄                       
            $gameRecord = new GameRecord();
            $gameRecord->getRecord();
            self::returnToMainMenu();
        } elseif ($selectAction == '4') {
            // 結束遊戲
            echo '遊戲結束';
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
