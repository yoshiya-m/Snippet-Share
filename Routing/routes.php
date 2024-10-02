<?php

use Database\MySQLWrapper;
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    "" => function($path): HTTPRenderer {
        return new HTMLRenderer("component/content", ["path" => $path]);
    }
    ,
    "create" => function(): HTTPRenderer {

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type");
            http_response_code(204);
            exit;
        }

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $inputText = $data['inputText'];
        $expirationDate = $data['expirationTime'];

        ValidationHelper::string($inputText);
        $url = DatabaseHelper::createSnippet($inputText, $expirationDate);

        return new JSONRenderer(["url" => $url]);
    },
    "content" => function(): JSONRenderer {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $path = $_GET['path'];
 
            $db = new MySQLWrapper();
            $stmt = $db->prepare("SELECT * FROM snippet WHERE path = ?");
            $stmt->bind_param("s", $path);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $content = $row["content"];
            return new JSONRenderer(["content" => $content]);
        }
        return new JSONRenderer(["content" => ""]);
    }
];