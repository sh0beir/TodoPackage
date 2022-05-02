<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use sh0beir\todo\Models\Label;
use sh0beir\todo\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testinsertData()
    {
        $data = factory(Task::class)->make();
        Task::create($data->toArray());

        $this->assertDatabaseHas('tasks', $data->toArray());
    }

    public function testTaskRelationshipWithLabel()
    {
        $count = rand(1, 10);

        $task = factory(Task::class)->create();
        $labels = factory(Label::class, $count)->create();

        $labels = $task->labels()->attach($labels);

        $this->assertCount($count, $task->labels);
    }
}
