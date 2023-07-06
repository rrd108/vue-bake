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

    public function testExecuteFilesCreated(): void
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsIndex.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsAdd.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsEdit.vue');
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsView.vue');
    }

    public function testExecuteFilesCreatedWithPath()
    {
        $this->exec('bake vue_component Posts --lang ts --path tmp');
        $this->assertExitSuccess();
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'tmp' . DS . 'PostsIndex.vue');
    }

    public function testExecuteIndexFileContentJs()
    {
        $this->exec('bake vue_component Posts --lang js');
        $this->assertExitSuccess();
        $content = file_get_contents(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsIndex.vue');
        $this->assertStringContainsString('<script setup>', $content);
        $this->assertStringContainsString('.then(res => (posts.value = res.data.posts))', $content);
        $this->assertStringContainsString('<span>id</span>', $content);
        $this->assertStringContainsString('<router-link :to="`/posts/${post.id}`">{{ post.id }}</router-link>', $content);
    }

    public function testExecuteIndexFileContentTs()
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS .  'PostInterface.ts');
        $content = file_get_contents(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsIndex.vue');
        $this->assertStringContainsString('<script setup lang="ts">', $content);
        $this->assertStringContainsString('import Post from \'@/types/Post\'', $content);
        $this->assertStringContainsString('  const posts = ref<Post[]>([])', $content);
        $this->assertStringContainsString('.then(res => (posts.value = res.data.posts))', $content);
        $this->assertStringContainsString('<span>id</span>', $content);
        $this->assertStringContainsString('<router-link :to="`/posts/${post.id}`">{{ post.id }}</router-link>', $content);
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
