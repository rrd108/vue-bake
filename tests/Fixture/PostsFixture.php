<?php

namespace VueBake\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture
{
    public $records = [
        ['id' => 1, 'author_id' => 2, 'title' => 'First Post', 'body' => 'First Post Body', 'published' => 'Y'],
        ['id' => 2, 'author_id' => 1, 'title' => 'Second Post', 'body' => 'Second Post Body', 'published' => 'Y'],
        ['id' => 1, 'author_id' => 2, 'title' => 'Third Post', 'body' => 'Third Post Body', 'published' => 'Y'],
    ];
}
