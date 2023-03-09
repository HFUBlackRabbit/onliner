<?php

namespace App\Controllers;

use App\Models\UserModel;
use JetBrains\PhpStorm\NoReturn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends AbstractController
{
    public function signUp(): string
    {
        return App()->render('signUp');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    public function signUpSubmit(): string
    {
        $errors = [];

        if (empty($_POST['login'])) {
            $errors['login'] = 'Empty Login';
        } elseif (UserModel::findOne(['login' => $_POST['login']]) != null) {
            $errors['login'] = 'Already exists';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = 'Empty password';
        } elseif ($_POST['password'] != $_POST['passwordRepeat']) {
            $errors['passwordRepeat'] = 'Password mismatch';
        }

        if (!empty($errors)) {
            return App()->render('signUp', compact('errors'));
        }

        $user = UserModel::create([
            'login' => $_POST['login'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
        ]);

        if ($user == null) {
            throw new \Exception('User not created!');
        }

        $_SESSION['userId'] = $user->id;
        App()->redirect('/');
    }

    public function signIn(): string
    {
        return App()->render('signIn');
    }

    public function signInSubmit(): string
    {
        $user = UserModel::findOne([
            'login' => $_POST['login']
        ]);

        $errors = [];
        if ($user == null) {
            $errors[] = 'Invalid login';
        }

        if (!password_verify($_POST['password'], $user->password)) {
            $errors[] = 'Invalid password';
        }

        if (!empty($errors)) {
            return App()->render('signIn', compact('errors'));
        }

        $_SESSION['userId'] = $user->id;
        App()->redirect('/');
    }

    #[NoReturn] public function logout(): void
    {
        $_SESSION['userId'] = null;
        App()->redirect('/');
    }
}