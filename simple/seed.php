<?php

define("CONFIG", require 'config.php');

$pdo = new PDO(...CONFIG['db']);

$pdo->query(file_get_contents('codes.sql'));

$count = 0;
$codes = [];

do {
    $count++;
    $code = bin2hex(random_bytes(5));
    $codes[] = $code;

    if ($count % 1e4 == 0) {
        echo $count . PHP_EOL;
        echo $code . PHP_EOL;

        $q = array_fill(0, 1e4, '(?)');

        $query = $pdo->prepare('INSERT INTO `codes` (value) VALUES ' . implode(', ', $q));
        $query->execute($codes);

        $codes = [];
    }
} while ($count < 5e5);