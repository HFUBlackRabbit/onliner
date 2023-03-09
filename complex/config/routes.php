<?php

use App\Controllers\MainController;
use App\Controllers\UserController;

return [
    'GET' => [
        '/' => [MainController::class, 'index'],
        '/code' => [MainController::class, 'code'],
        '/signIn' => [UserController::class, 'signIn'],
        '/signUp' => [UserController::class, 'signUp'],
        '/logout' => [UserController::class, 'logout']
    ],
    'POST' => [
        '/signIn' => [UserController::class, 'signInSubmit'],
        '/signUp' => [UserController::class, 'signUpSubmit'],
    ]
];