<?php
function generateRandomString($length = 16) {
    return bin2hex(random_bytes($length / 2)); // 16進数に変換
}

function generateRandomBytes($length = 16) {
    return random_bytes($length / 2);
}

// 使用例
$randomString = generateRandomString(16); // 16文字のランダムな文字列
echo $randomString . PHP_EOL;

$randomBytes = generateRandomBytes(16);
echo $randomBytes . PHP_EOL;
?>