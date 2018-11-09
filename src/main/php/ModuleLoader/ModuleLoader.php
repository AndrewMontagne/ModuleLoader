<?php

declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;

/**
 * Class ModuleLoader
 * @package ModuleLoader
 */
class ModuleLoader
{
    /**
     * The modules manifest.
     *
     * @var array
     */
    private static $modules;

    /**
     * Manually sets the modules manifest.
     *
     * @param array $modules
     */
    static public function setModules(array $modules): void
    {
        self::$modules = $modules;
    }

    /**
     * Loads the modules manifest from the specified filename. If filename is
     * omitted, loads from the default location.
     *
     * @param string $filename
     */
    static public function loadModules(
        string $filename = ManifestGenerator::MANIFEST_FILENAME
    ): void {
        self::$modules = require($filename);
    }

    /**
     * Generates and loads the modules manifest at runtime. Generally only
     * useful for development or debugging purposes.
     *
     * @var $path string Path to search from. Should be the root of the project.
     */
    static public function dynamicallyLoadModules(string $path = '.'): void {
        self::$modules = ManifestGenerator::generateManifest($path);
    }

    /**
     * Searches the manifest for modules in the specified category.
     *
     * @var $category string The module category to search for
     * @return ModuleDefinition[]
     */
    static public function getModulesForCategory(string $category): array
    {
        if (array_key_exists($category, self::$modules)) {
            return self::$modules[$category];
        } else {
            return [];
        }
    }
}