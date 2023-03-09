<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/boot.php';

register_shutdown_function(function () {
    if (error_get_last()) {
        http_response_code(500);
        echo 'Something wrong, try again later';
    }
});

session_start();

App()->handle();