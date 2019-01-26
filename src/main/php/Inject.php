<?php
declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

use MuPHP\Modules\ModuleLoader;

/**
 * @param ModuleLoader $moduleLoader
 */
function setupInjection(ModuleLoader $moduleLoader)
{
    global $muphp_modules_moduleloader_singleton;
    $muphp_modules_moduleloader_singleton = $moduleLoader;
}

/**
 * @param string $category
 * @param array $variables
 * @return \MuPHP\Modules\ModuleDefinition
 * @throws \MuPHP\Modules\ModuleException
 */
function injectInstance(string $category, array $variables = [])
{
    global $muphp_modules_moduleloader_singleton;
    return $muphp_modules_moduleloader_singleton->getModuleForCategory($category, $variables);
}
