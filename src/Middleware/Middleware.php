<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Middleware;

abstract class Middleware
{
    abstract public function handle(): void;
}
