<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Exception;

use RuntimeException;

final class CustomException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
