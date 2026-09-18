<?php

declare(strict_types=1);

namespace EduardoCenci\FileManager\Enum;

enum AlertType: string
{
    case Success = 'success';
    case Error = 'error';
    case Info = 'info';
}
