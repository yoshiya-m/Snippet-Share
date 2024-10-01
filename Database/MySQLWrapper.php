<?php

namespace Database;

use Helpers\Settings;

class MySQLWrapper extends mysqli {
    public function __construct(?string $hostname = 'localhost', string $username = null, string $password = null, string $database = null, int $port = null, string $socket = null)
    {
        // ERROR: エラーメッセージの自動表示. STRICT: 例外をスローするようになる
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $username = $username??Settings::env('DATABASE_USER');
        $password = $password??Settings::env('DATABASE_USER_PASSWORD');
        $database = $database??Settings::env('DATABASE_NAME');

        parent::__construct($hostname, $username, $password, $database, $port, $socket);

    }

    public function getDatabaseName(): string {
        return $this->query("Select database() AS the_db")->fetch_row()[0];
    }
}