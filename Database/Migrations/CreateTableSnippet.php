<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateTableSnippet implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE snippet (
                id INT PRIMARY KEY AUTO_INCREMENT,
                content VARCHAR(500) NOT NULL,
                expire_date DATETIME NOT NULL,
                url VARCHAR(500) NOT NULL,
                UNIQUE INDEX idx_unique_url (url),
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE posts"
        ];
    }
}
