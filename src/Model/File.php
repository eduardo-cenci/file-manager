<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Model;

final class File extends Model
{
    public ?int $userId = null;
    public ?int $folderId = null;
    public ?int $size = null;
    public string $name;
    public ?string $extension = null;
    public ?string $storageName = null;
    public ?string $mimeType = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
