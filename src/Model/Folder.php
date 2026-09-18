<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Model;

final class Folder extends Model
{
    public ?int $userId = null;
    public ?int $parentId = null;
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isRoot(): bool
    {
        return $this->parentId === null;
    }
}
