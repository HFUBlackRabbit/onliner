<?php

namespace App\Models;

class UserModel extends AbstractModel {
    public int $id;
    public string $login;
    public string $password;
}