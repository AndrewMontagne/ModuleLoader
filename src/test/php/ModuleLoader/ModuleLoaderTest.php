<?php
declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;

use PHPUnit\Framework\TestCase;

class ModuleLoaderTest extends TestCase
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
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn($categories,
            $categoryName);
        $category = $this->assertModuleContainsOneCategoryAndReturn($module,
            $categoryName);

        $this->assertEmpty($category->getVariables());
    }

    public function testComplexModule()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testComplexModule');

        $this->assertCount(1, $categories);

        $categoryName = 'ComplexModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn($categories,
            $categoryName);
        $category = $this->assertModuleContainsOneCategoryAndReturn($module,
            $categoryName);

        $variables = $category->getVariables();
        $this->assertNotEmpty($variables);
    }

    public function testNestedNamespace()
    {
        $categories = ManifestGenerator::generateManifest(getcwd() . '/src/test/fixtures/testNestedNamespace');
        $categoryName = 'NestedModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn($categories,
            $categoryName);
        $this->assertModuleContainsOneCategoryAndReturn($module,
            $categoryName);
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
        return $category[0];
    }

    private function assertModuleContainsOneCategoryAndReturn(
        ModuleDefinition $module,
        string $categoryName
    ): ModuleCategory {
        $moduleCategories = $module->getCategories();
        $this->assertNotEmpty($moduleCategories);
        $this->assertEquals(1, $this->count($moduleCategories));

        $category = $moduleCategories[0];
        $this->assertEquals($categoryName, $category->getName());

        return $category;
    }
}
