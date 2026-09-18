<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Model;

use DateTimeImmutable;

abstract class Model
{
    public ?int $id = null;
    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;
}
