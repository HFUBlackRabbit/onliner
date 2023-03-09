<?php

namespace App\Models;

class CodeModel extends AbstractModel
{
    public int $id;
    public string $value;
    public ?int $user_id;
    public ?string $received_at;
}