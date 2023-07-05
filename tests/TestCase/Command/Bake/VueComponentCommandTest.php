<?php

declare(strict_types=1);

namespace VueBake\Test\Command\Bake;

use Cake\TestSuite\TestCase;
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
        // Sets the TestApp namespace to be used instead of App
        $this->setAppNamespace();
        $this->configApplication(
            'TestApp\Application',
            [PLUGIN_TESTS . 'test_app' . DS . 'config']
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testExecute(): void
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        // Assert the generated files exist in the expected path
        $this->assertFileExists(ROOT . DS . 'src' . DS . 'VueComponents' . DS . 'PostsIndex.vue');
        $this->assertFileExists(ROOT . DS . 'src' . DS . 'VueComponents' . DS . 'PostsAdd.vue');
        $this->assertFileExists(ROOT . DS . 'src' . DS . 'VueComponents' . DS . 'PostsEdit.vue');
        $this->assertFileExists(ROOT . DS . 'src' . DS . 'VueComponents' . DS . 'PostsView.vue');
    }
}
