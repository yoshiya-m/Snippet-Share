<?php

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    'random/part'=>function(): HTTPRenderer {
        $part = DatabaseHelper::getRandomComputerPart();

        return new HTMLRenderer('component/random-part', ['part' => $part]);
    },
    'parts' => function(): HTTPRenderer {
        $id = ValidationHelper::integer($_GET['id']??null);

        $part = DatabaseHelper::getComputerPartById($id);
        return new HTMLRenderer('component/parts', ['part'=>$part]);
    },
    'api/random/part' => function(): HTTPRenderer {
        $part = DatabaseHelper::getRandomComputerPart();
        return new JSONRenderer(['part' => $part]);
    },
    'api/parts' => function() {
        $id = ValidationHelper::integer($_GET['id']??null);
        $part = DatabaseHelper::getComputerPartById($id);
        return new JSONRenderer(['part' => $part]);
    },
    'types' => function() {
        $type = ValidationHelper::string($_GET['type']??null);
        $page = ValidationHelper::integer($_GET['page']??null);
        $perpage = ValidationHelper::integer($_GET['perpage']??null);

        $part = DatabaseHelper::getComputerPartByType($type, $page, $perpage);
        return new JSONRenderer(['part' => $part]);
    },

    'random/computer' => function() {
        // コンピュータ生成は何の部品で構成？-> ランダムに4部品集めて返す
        $computer = DatabaseHelper::getRandomComputer();
        return new JSONRenderer(['computer' => $computer]);
    },
    'parts/newest' => function() {
        $page = ValidationHelper::integer($_GET['page']??null);
        $perpage = ValidationHelper::integer($_GET['perpage']??null);

        $part = DatabaseHelper::getNewestComputerPart($page, $perpage);
        return new JSONRenderer(['part' => $part]);
    },
    'parts/performance' => function(){
        $order = ValidationHelper::string($_GET['order']??null);
        $type = ValidationHelper::string($_GET['type']??null);
        
        $part = DatabaseHelper::getComputerPartByPerformance($order, $type);
        return new JSONRenderer(['part' => $part]);
    }   
];