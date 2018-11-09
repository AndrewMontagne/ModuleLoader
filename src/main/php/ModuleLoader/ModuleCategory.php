<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;

/**
 * Class ModuleCategory
 * @package ModuleLoader
 */
class ModuleCategory
{
    /**
     * @var string The name of the category
     */
    private $name;

    /**
     * @var string[] Any variables specified
     */
    private $variables;

    /**
     * ModuleCategory constructor.
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
     * @return ModuleCategory
     */
    public function setName(string $name): ModuleCategory
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
     * @return ModuleCategory
     */
    public function setVariables(array $variables): ModuleCategory
    {
        $this->variables = $variables;
        return $this;
    }
}