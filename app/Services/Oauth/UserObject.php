<?php

declare(strict_types=1);

namespace App\Services\Oauth;

class UserObject
{
    public function __construct(public int $id, public string $email)
    {
    }
}
