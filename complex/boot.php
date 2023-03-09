<?php

const ROOT = __DIR__;

require_once ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

if (!function_exists('App')) {
    function App(): \App\App
    {
        return \App\App::getInstance();
    }
}
