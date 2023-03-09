<?php

const TIME_EDGE = 2 ** 31 - 1;

define("CONFIG", require 'config.php');

register_shutdown_function(function () {
    if (error_get_last()) {
        http_response_code(500);
        echo 'Something wrong, try again later';
    }
});

session_start();

if (empty($_COOKIE['user'])) {
    setcookie('user', uniqid(more_entropy: true), [
        'expires' => TIME_EDGE,
        'httponly' => true,
        'secure' => true
    ]);
}

if ($_SERVER['REQUEST_URI'] == '/code') {
    $pdo = new PDO(...CONFIG['db']);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $query = $pdo->prepare('SELECT * FROM `codes` WHERE `user_uuid` = :uuid');
    $query->bindParam('uuid', $_COOKIE['user']);
    $query->execute();
    $code = $query->fetch();


    if (!$code) {
        $code = $pdo->query('SELECT * FROM `codes` WHERE `user_uuid` IS NULL LIMIT 1')->fetch();

        $query = $pdo->prepare('UPDATE `codes` SET `user_uuid` = :uuid, `received_at` = NOW() WHERE `id` = :code_id');
        $query->bindParam('uuid', $_COOKIE['user']);
        $query->bindParam('code_id', $code['id']);
        $query->execute();
    }

    $location = sprintf(CONFIG['redirect_url'], $code['value']);
    header('Location: ' . $location);
}

include 'view.php';