<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Exception;

use RuntimeException;

final class AppException extends RuntimeException
{
    public function __construct(
        public readonly array $messages,
        public readonly string $redirectTo,
    ) {
        unset($messages['__root__']);

        parent::__construct(implode("\n", $messages));
    }
}
