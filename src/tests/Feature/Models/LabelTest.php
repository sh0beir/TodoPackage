<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use sh0beir\todo\Models\Label;
use sh0beir\todo\Models\Task;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function testinsertData()
    {
        $data = factory(Label::class)->make();
        Label::create($data->toArray());

        $this->assertDatabaseHas('labels', $data->toArray());
    }

    public function testLabelRelationshipWithTask()
    {
        $count = rand(1, 10);

        $label = factory(Label::class)->create();
        $tasks = factory(Task::class, $count)->create();

        $labels = $label->tasks()->attach($tasks);

        $this->assertCount($count, $label->tasks);
    }
}
