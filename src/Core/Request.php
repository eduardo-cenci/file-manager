<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Core;

use EduardoCenci\FileManager\Exception\HttpException;

final readonly class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly int $size,
    ) {
        $this->checkSize();
    }

    public static function fromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $size = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return new self($method, $path, $size);
    }

    private function checkSize(): void
    {
        $maxSize = $this->getMaxSize();

        if ($maxSize > 0 && $this->size > $maxSize) {
            throw new HttpException(413, 'Request payload is too large');
        }
    }

    private function getMaxSize(): int
    {
        $value = trim((string) ini_get('post_max_size'));
        $number = (int) $value;
        $suffix = strtoupper(substr($value, -1));

        return match ($suffix) {
            'G' => $number * 1024 ** 3,
            'M' => $number * 1024 ** 2,
            'K' => $number * 1024,
            default => $number,
        };
    }
}
