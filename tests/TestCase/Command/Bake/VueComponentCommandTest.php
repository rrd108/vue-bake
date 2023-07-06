<?php

declare(strict_types=1);

namespace VueBake\Test\Command\Bake;

use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Cake\Datasource\ConnectionManager;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;

class VueComponentCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    protected $fixtures = [
        'plugin.VueBake.Posts',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->setAppNamespace('VueBake\Test\App');
        $this->configApplication(
            'TestApp\Application',
            [PLUGIN_TESTS . 'test_app' . DS . 'config']
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->removeTestGeneratedFiles(PLUGIN_TESTS . '..' . DS . 'TestApp');
    }

    public function testExecute(): void
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        // Assert the generated files exist in the expected path
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsIndex.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsAdd.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsEdit.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsView.vue');
    }

    private function removeTestGeneratedFiles($directoryPath)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($directoryPath);
    }
}
