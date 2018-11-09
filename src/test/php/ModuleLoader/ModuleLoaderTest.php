<?php
declare(strict_types=1);
/**
 * Copyright 2018 Andrew O'Rourke
 */

namespace ModuleLoader;

use PHPUnit\Framework\TestCase;

class ModuleLoaderTest extends TestCase
{
    private $originalCwd = null;

    public function testNoModulesPresent()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testNoModules');
        $categories = ManifestGenerator::generateManifest();
        $this->assertEmpty($categories);
    }

    public function testDontScanForbiddenDirectories()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testDontScanForbiddenDirectories');
        $categories = ManifestGenerator::generateManifest();
        $this->assertEmpty($categories);
    }

    public function testSimpleModule()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testSimpleModule');
        $categories = ManifestGenerator::generateManifest();

        $this->assertCount(1, $categories);

        $categoryName = 'SimpleModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn($categories, $categoryName);
        $category = $this->assertModuleContainsOneCategoryAndReturn($module, $categoryName);

        $this->assertEmpty($category->getVariables());
    }

    public function testComplexModule()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testComplexModule');
        $categories = ManifestGenerator::generateManifest();

        $this->assertCount(1, $categories);

        $categoryName = 'ComplexModule';
        $module = $this->assertManifestContainsACategoryWithOneModuleAndReturn($categories, $categoryName);
        $category = $this->assertModuleContainsOneCategoryAndReturn($module, $categoryName);

        $variables = $category->getVariables();
        $this->assertNotEmpty($variables);
    }

    private function assertManifestContainsACategoryWithOneModuleAndReturn(array $categories, string $categoryName): ModuleDefinition {
        $this->assertNotEmpty($categories);
        $this->assertArrayHasKey($categoryName, $categories);
        $category = $categories[$categoryName];
        $this->assertNotEmpty($category);
        $this->assertEquals(1, $this->count($category));
        return $category[0];
    }

    private function assertModuleContainsOneCategoryAndReturn(ModuleDefinition $module, string $categoryName): ModuleCategory {
        $moduleCategories = $module->getCategories();
        $this->assertNotEmpty($moduleCategories);
        $this->assertEquals(1, $this->count($moduleCategories));

        $category = $moduleCategories[0];
        $this->assertEquals($categoryName, $category->getName());

        return $category;
    }

    protected function setUp()
    {
        $this->originalCwd = getcwd();
    }

    protected function tearDown()
    {
        @unlink(ManifestGenerator::MANIFEST_FILENAME);
        chdir($this->originalCwd);
    }
}
