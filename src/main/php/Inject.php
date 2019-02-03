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
 * @param string $module
 * @param array $variables
 * @return \MuPHP\Modules\ModuleContainer
 * @throws \MuPHP\Modules\ModuleException
 */
function injectInstance(string $module, array $variables = [])
{
    global $muphp_modules_moduleloader_singleton;
    return $muphp_modules_moduleloader_singleton->getModule($module, $variables);
}
