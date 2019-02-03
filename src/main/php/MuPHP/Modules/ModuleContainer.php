<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace MuPHP\Modules;

/**
 * Class ModuleContainer
 * @package Modules
 */
class ModuleContainer
{
    /**
     * @var string The namespace of the module
     */
    private $namespace;

    /**
     * @var Module[] The modules this container has
     */
    private $modules;

    /**
     * @var string The class name of the module
     */
    private $className;

    /**
     * ModuleContainer constructor.
     * @param string $namespace
     * @param array $modules
     * @param string $className
     */
    public function __construct(
        string $namespace,
        array $modules,
        string $className
    ) {
        $this->namespace = $namespace;
        $this->modules = $modules;
        $this->className = $className;
    }

    public function __toString()
    {
        return $this->getFullyQualifiedClassName();
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
     * @return ModuleContainer
     */
    public function setNamespace(string $namespace): ModuleContainer
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return Module[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param array $modules
     * @return ModuleContainer
     */
    public function setModules(array $modules): ModuleContainer
    {
        $this->modules = $modules;
        return $this;
    }

    /**
     * Gets a Module with the specified $name, if it exists.
     *
     * @param string $name
     * @return Module
     */
    public function getModule(string $name): Module
    {
        foreach ($this->modules as $module) {
            if ($module->getName() == $name) {
                return $module;
            }
        }
        return null;
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
     * @return ModuleContainer
     */
    public function setClassName(string $className): ModuleContainer
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
