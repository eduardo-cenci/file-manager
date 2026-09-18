<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Model;

final class User extends Model
{
    public string $password;

    public function __construct(
        public string $name,
        public string $email,
    ) {
        $this->name = $name;
        $this->email = $email;
    }
}
