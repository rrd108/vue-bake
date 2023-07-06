<?php

namespace VueBake\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture
{
    public $records = [
        ['id' => 1, 'author_id' => 2, 'title' => 'First Post', 'body' => 'First Post Body', 'published' => 'Y']
    ];
}
