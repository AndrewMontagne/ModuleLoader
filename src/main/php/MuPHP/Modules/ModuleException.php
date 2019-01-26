<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace MuPHP\Modules;

use Throwable;

class ModuleException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
