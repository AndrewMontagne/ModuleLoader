<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace MuPHP\Modules;

/**
 * Class Modules
 * @package Modules
 */
class ModuleLoader
{
    /**
     * The modules manifest.
     *
     * @var array
     */
    private $modules;

    /**
     * ModuleLoader constructor.
     * @param array $modules
     */
    private function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * Manually sets the modules manifest.
     *
     * @param array $modules
     */
    public function setModules(array $modules): void
    {
        self::$modules = $modules;
    }

    /**
     * Loads the modules manifest from the specified filename. If filename is
     * omitted, loads from the default location.
     *
     * @param string $filename
     * @return ModuleLoader
     */
    public static function loadModules(
        string $filename = ManifestGenerator::MANIFEST_FILENAME
    ): ModuleLoader {
        $modules = require($filename);
        return new ModuleLoader($modules);
    }

    /**
     * Generates and loads the modules manifest at runtime. Generally only
     * useful for development or debugging purposes.
     *
     * @var $path string Path to search from. Should be the root of the project.
     * @return ModuleLoader
     */
    public static function dynamicallyLoadModules(string $path = '.'): ModuleLoader
    {
        $modules = ManifestGenerator::generateManifest($path);
        return new ModuleLoader($modules);
    }

    /**
     *
     * Searches the manifest for modules in the specified $module.
     *
     * @var $category string The module to search for
     * @return ModuleContainer[]
     */
    public function getModules(string $moduleName, array $variables = []): array
    {
        if (array_key_exists($moduleName, $this->modules)) {
            $moduleContainers = $this->modules[$moduleName];
            if (empty($variables)) {
                return $moduleContainers;
            } else {
                $matchingModules = [];
                foreach ($moduleContainers as $moduleContainer) {
                    $module = $moduleContainer->getModule($moduleName);
                    if (is_null($module)) {
                        continue;
                    }
                    if (array_intersect($module->getVariables(), $variables) == $variables) {
                        $matchingModules[] = $moduleContainer;
                    }
                }
                return $matchingModules;
            }
        } else {
            return [];
        }
    }

    /**
     * Searches the manifest for a single module in the specified module.
     * Throws an exception if there is less than or more than one available.
     *
     * @param string $name
     * @return ModuleContainer
     * @throws ModuleException
     */
    public function getModule(string $name): ModuleContainer
    {
        $modules = $this->getModules($name);

        switch (count($modules)) {
            case 0:
                throw new ModuleException("Could not find any modules for module '$name'!");
                break;
            case 1:
                return array_pop($modules);
                break;
            default:
                throw new ModuleException("More than one module available for module '$name'!");
        }
    }
}
