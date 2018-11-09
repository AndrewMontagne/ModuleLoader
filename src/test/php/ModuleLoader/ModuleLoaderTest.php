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

    public function testNoAutoloadPresent()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testNoModules');
        $modules = ManifestGenerator::generateManifest();

        $this->assertEmpty($modules);
    }

    public function testDontScanForbiddenDirectories()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testDontScanForbiddenDirectories');
        $modules = ManifestGenerator::generateManifest();

        $this->assertEmpty($modules);
    }

    public function testSimpleModule()
    {
        chdir($this->originalCwd . '/src/test/fixtures/testSimpleModule');
        $modules = ManifestGenerator::generateManifest();

        $this->assertNotEmpty($modules);
        $this->assertEquals(1, $this->count($modules));

        $simpleModules = $modules["SimpleModule"];
        $this->assertNotEmpty($simpleModules);
        $this->assertEquals(1, $this->count($simpleModules));

        $module = $simpleModules[0];
        $this->assertInstanceOf('ModuleLoader\ModuleDefinition', $module);

        $moduleCategories = $module->getCategories();
        $this->assertNotEmpty($moduleCategories);
        $this->assertEquals(1, $this->count($moduleCategories));

        $moduleCategory = $moduleCategories[0];
        $this->assertEquals("SimpleModule", $moduleCategory->getName());
        $this->assertEmpty($moduleCategory->getVariables());
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
