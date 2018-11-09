<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;

/**
 * Class ModuleDefinition
 * @package ModuleLoader
 */
class ModuleDefinition
{
    /**
     * @var string The namespace of the module
     */
    private $namespace;

    /**
     * @var ModuleCategory[] The categories the module belongs to
     */
    private $categories;

    /**
     * @var string The class name of the module
     */
    private $className;

    /**
     * ModuleDefinition constructor.
     * @param string $namespace
     * @param array $categories
     * @param string $className
     */
    public function __construct(
        string $namespace,
        array $categories,
        string $className
    ) {
        $this->namespace = $namespace;
        $this->categories = $categories;
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return ModuleDefinition
     */
    public function setNamespace(string $namespace): ModuleDefinition
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return ModuleCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     * @return ModuleDefinition
     */
    public function setCategories(array $categories): ModuleDefinition
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return ModuleDefinition
     */
    public function setClassName(string $className): ModuleDefinition
    {
        $this->className = $className;
        return $this;
    }

    /**
     * Returns the fully qualified class name
     * @return string
     */
    public function getFullyQualifiedClassName(): string
    {
        return "$this->namespace\\$this->className";
    }

    /**
     * Creates and returns an instance of the module
     *
     * @param mixed ...$args
     * @return object
     * @throws \ReflectionException
     */
    public function create(...$args): object
    {
        $class = new \ReflectionClass($this->getFullyQualifiedClassName());
        return $class->newInstanceArgs($args);
    }
}