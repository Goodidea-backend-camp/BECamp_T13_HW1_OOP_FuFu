<div align="center">

# 對戰遊戲

![Ubuntu Badge](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white) ![PHP Badge](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![MySQL Badge](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white) ![Git Badge](https://img.shields.io/badge/GIT-E44C30?style=for-the-badge&logo=git&logoColor=white) ![GitHub Badge](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)

</div>

## 專案簡介
**對戰遊戲**是一個使用 **物件導向程式設計（OOP）** 概念設計的簡易遊戲，玩家可以在終端機上運行並進行對戰。
遊戲中玩家可以自創角色類型，並且玩家將與怪物對戰，挑戰過程中會提升角色的能力值。

## 功能
- **玩家角色類型**：選擇不同的角色職業，每個職業有不同的屬性。
- **與怪物對戰**：每一關的怪物有不同的能力值，擊敗怪物後可獲得經驗和恢復生命。
- **查看遊戲紀錄**：顯示已挑戰的關卡數量、擊敗的怪物以及挑戰的開始和結束時間。

## 觀念
遊戲的設計基於 **物件導向程式設計（OOP）** 概念，包含以下主要特性：
- **Classes/Objects**：遊戲中有不同的類別（像是Character、Fight、Database...等），每個類別都是一個物件，擁有屬性和方法。
- **Constructor**：Fight類別包含建構子方法，初始化物件的基本屬性，例如角色與敵人的生命值、攻擊力等。
- **Access Modifiers**：使用存取修飾符（如 `private`、`public`、`protected`）來保護類別屬性，控制屬性和方法的可訪問性。
- **Inheritance**：不同角色職業（如法師、戰士）會繼承自基本角色類別。
- **Class Constants**：使用類常數來定義不變的遊戲屬性，例如遊戲的最大等級。
- **Static Methods**：遊戲紀錄的統計功能會使用靜態方法來調用，無需實例化物件。
- **Namespace**：為了組織代碼，將不同的功能模組放入不同的命名空間中，減少命名衝突。

## 功能詳解

### 1. 主選單
執行程式後，玩家將進入主選單，並可選擇以下操作：
- **新建角色**：創建一個新角色。
- **開始遊戲**：選擇並開始遊戲。
- **查看遊戲紀錄**：查看已挑戰的關卡和敵人紀錄。

### 2. 新建角色
創建角色時，玩家需要：
- 輸入角色名稱。
- 選擇角色職業：不同職業初始值有不能的能力值。例如，法師的魔力攻擊和魔法防禦力較高。
- 設定角色的初始能力值：玩家將有 10 點可以自由分配到各項能力值上(物理攻擊力、魔法攻擊力、物理防禦力、魔法防禦力、魔力值、幸運值)
- **幸運值**：此屬性可以增強防禦能力。

### 3. 開始遊戲
- 玩家需要先創建至少一隻角色，才能開始遊戲。
- 遊戲開始後，玩家可以選擇一隻角色來進行遊戲。
- 每一關將有相對應的敵人，擊敗敵人後，角色將恢復生命值並獲得經驗值。
- 每五關後，玩家將挑戰一隻魔王。


### 4. 查看遊戲紀錄
玩家可以查看以下遊戲紀錄：
- 總共挑戰了多少關卡。
- 每次挑戰的開始和結束時間。

## 工具
- PHP 8.3
- MySQL

## 使用說明
1. 安裝 PHP 8.3 和 MySQL。
2. 在終端機中執行 `php index.php`，即可進入遊戲。
