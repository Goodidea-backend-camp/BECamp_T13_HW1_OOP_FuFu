<?php

namespace PlayGame;

require_once 'Database.php';

class GameRecord
{
    private $fight;
    private $database;
    private $startTime;
    private $endTime;

    public function __construct($fight = null)
    {
        $this->fight = $fight;
        $this->database = new Database();
    }

    public function start(): void
    {
        $this->startTime = date('Y-m-d H:i:s');
    }

    public function gameOver(): void
    {
        echo "☂☂☂ 遊戲結束，玩家失敗，敵人勝利 ☂☂☂\n";
        echo "╔═══╗╔══╗╔╗  ╔╗╔═══╗   ╔══╗╔╗╔╗╔═══╗╔═══╗\n";
        echo "║╔══╝║╔╗║║║  ║║║╔══╝   ║╔╗║║║║║║╔══╝║╔═╗║\n";
        echo "║║╔═╗║╚╝║║╚╗╔╝║║╚══╗   ║║║║║║║║║╚══╗║╚═╝║\n";
        echo "║║╚╗║║╔╗║║╔╗╔╗║║╔══╝   ║║║║║╚╝║║╔══╝║╔╗╔╝\n";
        echo "║╚═╝║║║║║║║╚╝║║║╚══╗   ║╚╝║╚╗╔╝║╚══╗║║║║ \n";
        echo "╚═══╝╚╝╚╝╚╝  ╚╝╚═══╝   ╚══╝ ╚╝ ╚═══╝╚╝╚╝ \n";
    }
    public function gameEnd(): void
    {
        $this->endTime = date('Y-m-d H:i:s');
        $this->saveRecord();
    }


    private function saveRecord(): void
    {
        $this->database->saveRecord(
            $this->fight->name,
            $this->fight->level,
            $this->startTime,
            $this->endTime
        );
    }
    public function getRecord()
    {
        $playerRecordName = readline('請輸入玩家名稱:');
        $recordDatas = $this->database->getRecord($playerRecordName);
        if ($recordDatas) {
            foreach ($recordDatas as $recordData) {
                echo "\n";
                echo "\n══════════════════════════════\n";
                echo "     玩家 {$recordData['player_name']} 的遊戲紀錄\n";
                echo "══════════════════════════════\n";
                echo "使用的職業: {$recordData['character_role']}\n";
                echo "玩家等級: {$recordData['player_levels']}\n";
                echo "開始時間: {$recordData['start_time']}\n";
                echo "結束時間: {$recordData['end_time']}\n";
                echo "══════════════════════════════\n\n";
            }
        } else {
            echo "沒有找到該玩家的遊戲紀錄。\n";
        }
    }
}
