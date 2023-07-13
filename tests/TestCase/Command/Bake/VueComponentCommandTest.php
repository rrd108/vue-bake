<?php

declare(strict_types=1);

namespace VueBake\Test\Command\Bake;

use Cake\TestSuite\TestCase;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;

class VueComponentCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    protected $fixtures = [
        'plugin.VueBake.Posts',
    ];

    private $basePath = PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS;

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
        $this->assertFileExists($this->basePath . 'PostsIndex.vue');
        $this->assertFileExists($this->basePath . 'PostsAdd.vue');
        $this->assertFileExists($this->basePath . 'PostsEdit.vue');
        $this->assertFileExists($this->basePath . 'PostsView.vue');
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
        $content = file_get_contents($this->basePath . 'PostsIndex.vue');
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
        $this->assertStringContainsString('const posts = ref<Post[]>([])', $content);
        $this->assertStringContainsString('.then(res => (posts.value = res.data.posts))', $content);
        $this->assertStringContainsString('<span>id</span>', $content);
        $this->assertStringContainsString('<router-link :to="`/posts/${post.id}`">{{ post.id }}</router-link>', $content);
    }

    public function testExecuteViewFileContentTs()
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        $this->assertFileExists(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS .  'PostInterface.ts');
        $content = file_get_contents(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsView.vue');
        $this->assertStringContainsString('<script setup lang="ts">', $content);
        $this->assertStringContainsString('import Post from \'@/types/Post\'', $content);

        $this->assertStringContainsString('const post = ref<Post>()', $content);
        $this->assertStringContainsString('.then(res => (post.value = res.data.post))', $content);
        $this->assertStringContainsString('const route = useRoute()', $content);
        $this->assertStringContainsString('.get(`${import.meta.env.VITE_APP_API_URL}post/${route.params.id}.json`)', $content);
        $this->assertStringContainsString('<dt>id</dt>', $content);
        $this->assertStringContainsString('<dd>{{ post.id }}</dd>', $content);
    }

    public function testExecuteAddFileContentTs()
    {
        $this->exec('bake vue_component Posts --lang ts');
        $this->assertExitSuccess();
        $content = file_get_contents(PLUGIN_TESTS . '..' . DS . 'TestApp' . DS . 'VueComponents' . DS . 'PostsAdd.vue');
        $this->assertStringContainsString('<script setup lang="ts">', $content);
        $this->assertStringContainsString('import Post from \'@/types/Post\'', $content);

        $this->assertStringContainsString('const post = ref<Post>()', $content);
        $this->assertStringContainsString('const createNewPost = () => {', $content);
        $this->assertStringContainsString('.post(`${import.meta.env.VITE_APP_API_URL}post.json`, post.value)', $content);
        $this->assertStringContainsString('<form @submit.prevent=createNewPost>', $content);
        $this->assertStringContainsString('<label>title</label>', $content);
        $this->assertStringContainsString('<input type="text" v-model={{ post.title }} />', $content);
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
