<?php

namespace Helpers;

use Database\MySQLWrapper;
use DateTime;
use Exception;

class DatabaseHelper
{
    public static function getRandomComputerPart(): array{
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartById(int $id): array{
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartByType(string $type, int $page, int $perpage): array{
        // page, perpageから返す部品の数とid?を決める
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE type = ? LIMIT ? OFFSET ?");
        $offset = ($page -1) * $perpage;
        $stmt->bind_param("sii", $type, $perpage, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        $parts = $result->fetch_all();
        // 1page目なら perpage * (page - 1)番目からperpage分取得する

        if (!$parts) throw new Exception('Could not find parts in database');

        return $parts;
    }

    public static function getRandomComputer(): array{
        // 4部品ランダムで抽出して返す
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT id FROM computer_parts");
        $stmt->execute();
        $result = $stmt->get_result();
        $ids = $result->fetch_all();
        if (!$ids) throw new Exception('Could not find a single part in database');
        $computer = [];
        $PARTS_QUANTITY = 4;

        for ($i = 0; $i < $PARTS_QUANTITY; $i++){
            $id = $ids[array_rand($ids)][0];
            $part = self::getComputerPartById($id);
            array_push($computer, $part);
        }
        return $computer;

    }

    public static function getNewestComputerPart(int $page, int $perpage): array{
        // 最新のcomputer partを取得
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts ORDER BY release_date desc LIMIT ? OFFSET ?");
        $offset = ($page -1) * $perpage;
        $stmt->bind_param("ii",  $perpage, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        $part = $result->fetch_all();

        if (!$part) throw new Exception('Could not find a single part in database');
        return $part;
    }

    public static function getComputerPartByPerformance(string $order, string $type): array{
        $db = new MySQLWrapper();

        $validOrders = ['asc', 'ASC', 'desc', 'DESC'];
        if (!in_array($order, $validOrders)) throw new Exception('Order is not valid');


        $sql = "SELECT * FROM computer_parts WHERE type = ? ORDER BY performance_score $order LIMIT 50";
        $stmt = $db->prepare($sql);

        $stmt->bind_param("s", $type);
        $stmt->execute();

        $result = $stmt->get_result();
        $parts = $result->fetch_all();
        if (!$parts) throw new Exception('Could not find a single part in database');

        return $parts;
    }

    
    public static function createSnippet(string $inputText, string $expirationTime): array {
        // ランダムに文字列を作成してuRLを作る
        // そのURLが存在するか確認
        // ある場合は、3回つくる
        $validExpirationTime = ['+10 minutes', '+1 hour', '+1 day'];
        if (!in_array($expirationTime, $validExpirationTime)) throw new Exception("expiration time is invalid.");
        $db = new MySQLWrapper();
        
        $CREATE_TIMES = 3;
        for ($i = 0; $i < $CREATE_TIMES; $i++) {
            $randomUrl = self::generateRandomString();
            $stmt = $db->prepare("SELECT * FROM snippet WHERE url = ?");
            $stmt->bind_param("s", $randomUrl);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) break;
            if ($i >= 2) throw new Exception("Could not make a unique URL."); 
        }

        $expireDate = new DateTime()->modify($expirationTime);
        $db = new MySQLWrapper();
        $stmt = $db->prepare("
            INSERT INTO snippet (content, expire_date, url) ]
            values (?, ?, ?);"
        );
        $stmt->bind_param("sss", $inputText, $expireDate, $randomUrl);
        $stmt->execute();
        return $randomUrl;
    }

    public static function generateRandomString($length = 20): string{
        return bin2hex(random_bytes($length / 2)); // 16進数に変換
    }

    public static function getContent(): array {

    }
    
}


