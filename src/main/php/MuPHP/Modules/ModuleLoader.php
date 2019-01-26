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
     * Searches the manifest for modules in the specified category.
     *
     * @var $category string The module category to search for
     * @return ModuleDefinition[]
     */
    public function getModulesForCategory(string $categoryName, array $variables = []): array
    {
        if (array_key_exists($categoryName, $this->modules)) {
            $modules = $this->modules[$categoryName];
            if (empty($variables)) {
                return $modules;
            } else {
                $matchingModules = [];
                foreach ($modules as $module) {
                    $category = $module->getCategory($categoryName);
                    if (is_null($category)) {
                        continue;
                    }
                    if (array_intersect($category->getVariables(), $variables) == $variables) {
                        $matchingModules[] = $module;
                    }
                }
                return $matchingModules;
            }
        } else {
            return [];
        }
    }

    /**
     * Searches the manifest for a single module in the specified category.
     * Throws an exception if there is less than or more than one available.
     *
     * @param string $category
     * @return ModuleDefinition
     * @throws ModuleException
     */
    public function getModuleForCategory(string $category): ModuleDefinition
    {
        $modules = $this->getModulesForCategory($category);

        switch (count($modules)) {
            case 0:
                throw new ModuleException("Could not find any modules for category '$category'!");
                break;
            case 1:
                return array_pop($modules);
                break;
            default:
                throw new ModuleException("More than one module available for category '$category'!");
        }
    }
}
