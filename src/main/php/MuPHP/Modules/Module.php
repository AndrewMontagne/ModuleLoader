<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace MuPHP\Modules;

/**
 * Class Module
 * @package Modules
 */
class Module
{
    /**
     * @var string The name of the module
     */
    private $name;

    /**
     * @var string[] Any variables specified
     */
    private $variables;

    /**
     * Module constructor.
     * @param string $name
     * @param string[] $variables
     */
    public function __construct(string $name, array $variables = [])
    {
        $this->name = $name;
        $this->variables = $variables;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Module
     */
    public function setName(string $name): Module
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param string[] $variables
     * @return Module
     */
    public function setVariables(array $variables): Module
    {
        $this->variables = $variables;
        return $this;
    }
}
