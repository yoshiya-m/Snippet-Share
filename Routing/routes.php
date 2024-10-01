<?php
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    "create" => function(): HTTPRenderer {
    // 返すデータをどこで作成する? component/create.phpで作成？
    // 返したいのはJSONデータで成功、失敗とURLを返したい
    // しかし、HTMLrenはhtmlを返す
    // JSOnはjsonデータだがほかのエンドポイントはデータ直渡し
    // database helperに渡して結果を返す

        // データの受け取り
        $inputText = $_POST['inputText'];
        $expirationDate = $_POST['expirationDate'];
        // データの検証
        ValidationHelper::string($inputText);
        // ここでjsonデータ作ってjsonrenderに投げる
        $url = DatabaseHelper::createSnippet($inputText, $expirationDate);

        return new JSONRenderer(["url" => $url]);
    }
];