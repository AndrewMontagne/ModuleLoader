<?php
declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace MuPHP\Modules;

use PHPUnit\Framework\TestCase;

class ManifestGeneratorTest extends TestCase
{
    public function testNoModulesPresent()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testNoModules');
        $this->assertEmpty($categories);
    }

    public function testDontScanForbiddenDirectories()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testDontScanForbiddenDirectories');
        $this->assertEmpty($categories);
    }

    public function testSimpleModule()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testSimpleModule');

        $this->assertCount(1, $categories);

        $moduleName = 'SimpleModule';
        $moduleContainer = $this->assertManifestContainsAClassWithOneModuleAndReturn(
            $categories,
            $moduleName
        );
        $module = $this->assertModuleContainsOneClassAndReturn(
            $moduleContainer,
            $moduleName
        );

        $this->assertEmpty($module->getVariables());
    }

    public function testComplexModule()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testComplexModule');

        $this->assertCount(1, $categories);

        $moduleName = 'ComplexModule';
        $moduleContainer = $this->assertManifestContainsAClassWithOneModuleAndReturn(
            $categories,
            $moduleName
        );
        $module = $this->assertModuleContainsOneClassAndReturn(
            $moduleContainer,
            $moduleName
        );

        $variables = $module->getVariables();
        $this->assertNotEmpty($variables);
    }

    public function testNestedNamespace()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testNestedNamespace');
        $moduleName = 'NestedModule';
        $moduleContainer = $this->assertManifestContainsAClassWithOneModuleAndReturn(
            $categories,
            $moduleName
        );
        $this->assertModuleContainsOneClassAndReturn(
            $moduleContainer,
            $moduleName
        );
    }

    public function testVariableModules()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testVariabledModule');

        $this->assertCount(1, $categories);
        $modules = $categories['VariableModule'];
        $this->assertCount(3, $modules);

        $class1Vars = $modules['TestNamespace\\TestClass']->getModule('VariableModule')->getVariables();
        $class2Vars = $modules['TestNamespace\\TestClassTwo']->getModule('VariableModule')->getVariables();
        $class3Vars = $modules['TestNamespace\\TestClassThree']->getModule('VariableModule')->getVariables();

        $this->assertCount(1, $class1Vars);
        $this->assertCount(0, $class2Vars);
        $this->assertCount(2, $class3Vars);

        $this->assertArrayHasKey('Test', $class1Vars);
        $this->assertArrayHasKey('Test', $class3Vars);
        $this->assertArrayHasKey('Test2', $class3Vars);
    }

    public function testQuotedModules()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testQuotedModule');

        $this->assertCount(1, $categories);
        $modules = $categories['QuotedModule'];
        $this->assertCount(1, $modules);
    }

    private function assertManifestContainsAClassWithOneModuleAndReturn(
        array $modules,
        string $moduleName
    ): ModuleContainer {
        $this->assertNotEmpty($modules);
        $this->assertArrayHasKey($moduleName, $modules);
        $module = $modules[$moduleName];
        $this->assertNotEmpty($module);
        $this->assertEquals(1, $this->count($module));
        return array_pop($module);
    }

    private function assertModuleContainsOneClassAndReturn(
        ModuleContainer $module,
        string $moduleName
    ): Module {
        $moduleCategories = $module->getModules();
        $this->assertNotEmpty($moduleCategories);
        $this->assertEquals(1, $this->count($moduleCategories));

        $module = array_pop($moduleCategories);
        $this->assertEquals($moduleName, $module->getName());

        return $module;
    }
}
