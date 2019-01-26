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

        $categoryName = 'SimpleModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn(
            $categories,
            $categoryName
        );
        $category = $this->assertModuleContainsOneCategoryAndReturn(
            $module,
            $categoryName
        );

        $this->assertEmpty($category->getVariables());
    }

    public function testComplexModule()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testComplexModule');

        $this->assertCount(1, $categories);

        $categoryName = 'ComplexModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn(
            $categories,
            $categoryName
        );
        $category = $this->assertModuleContainsOneCategoryAndReturn(
            $module,
            $categoryName
        );

        $variables = $category->getVariables();
        $this->assertNotEmpty($variables);
    }

    public function testNestedNamespace()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testNestedNamespace');
        $categoryName = 'NestedModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn(
            $categories,
            $categoryName
        );
        $this->assertModuleContainsOneCategoryAndReturn(
            $module,
            $categoryName
        );
    }

    public function testVariableModules()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testVariabledModule');

        $this->assertCount(1, $categories);
        $modules = $categories['VariableModule'];
        $this->assertCount(3, $modules);

        $class1Vars = $modules['TestNamespace\\TestClass']->getCategory('VariableModule')->getVariables();
        $class2Vars = $modules['TestNamespace\\TestClassTwo']->getCategory('VariableModule')->getVariables();
        $class3Vars = $modules['TestNamespace\\TestClassThree']->getCategory('VariableModule')->getVariables();

        $this->assertCount(1, $class1Vars);
        $this->assertCount(0, $class2Vars);
        $this->assertCount(2, $class3Vars);

        $this->assertArrayHasKey('Test', $class1Vars);
        $this->assertArrayHasKey('Test', $class3Vars);
        $this->assertArrayHasKey('Test2', $class3Vars);
    }

    private function assertManifestContainsACategoryWithOneModuleAndReturn(
        array $categories,
        string $categoryName
    ): ModuleDefinition {
        $this->assertNotEmpty($categories);
        $this->assertArrayHasKey($categoryName, $categories);
        $category = $categories[$categoryName];
        $this->assertNotEmpty($category);
        $this->assertEquals(1, $this->count($category));
        return array_pop($category);
    }

    private function assertModuleContainsOneCategoryAndReturn(
        ModuleDefinition $module,
        string $categoryName
    ): ModuleCategory {
        $moduleCategories = $module->getCategories();
        $this->assertNotEmpty($moduleCategories);
        $this->assertEquals(1, $this->count($moduleCategories));

        $category = array_pop($moduleCategories);
        $this->assertEquals($categoryName, $category->getName());

        return $category;
    }
}
