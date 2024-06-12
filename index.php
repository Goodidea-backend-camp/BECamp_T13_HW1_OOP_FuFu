<?php

namespace PlayGame;

require_once 'classes//View.php'; // 創建角色
class index
{
    public function __construct()
    {
        $View = new View();
        View::mainScreen();
    }
}
$index = new index();
