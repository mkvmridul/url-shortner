<?php
include "../php/config.php";
include "../php/ShortUrl.php";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST .";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
}
catch (PDOException $e) {
    trigger_error("failed to connect to db");
    exit;
}

$hash_url = new Hash_url($pdo);