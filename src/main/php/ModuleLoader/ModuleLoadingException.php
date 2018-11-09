<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;


use Throwable;

class ModuleLoadingException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}