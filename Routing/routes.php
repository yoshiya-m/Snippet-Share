<?php
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    "sample" => function(): HTTPRenderer {
    // 返すデータをどこで作成する? ここで作成してrendererに渡す？



        return new HTMLRenderer('component/sample');
    }
];