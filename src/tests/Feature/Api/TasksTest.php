<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use sh0beir\todo\Models\Label;
use sh0beir\todo\Models\Task;

use sh0beir\todo\Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    private string $routePrefix = 'api.tasks.';

    /** @test */
    public function get_all_user_tasks()
    {
        $user = factory(User::class)->create();
        $task = $user->tasks()->create(factory(Task::class)->make()->toArray());

        $response = $this->actingAs($user, 'api')->getJson(route($this->routePrefix . 'index'));
        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'labels' => $task->labels()->get()->pluck('label')->toArray(),
                ]
            ]
        ]);
    }

    /** @test */
    //test filter tasks by label
    public function filterTasksByLabel()
    {
        $user = factory(User::class)->create();
        $label = factory(Label::class)->create();
        $newTask = factory(Task::class)->make()->toArray();
        $task = $user->tasks()->create($newTask);

        $task->labels()->attach($label);


        $response = $this->actingAs($user, 'api')->getJson(route($this->routePrefix . 'filter', $label->id));
        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                ]
            ]
        ]);
    }

    /** @test */
    public function can_store_a_task()
    {
        // Build a non-persisted Task factory model.
        $user = factory(User::class)->create();
        $newTask = factory(Task::class)->make(['author_id' => $user->id, 'status' => false])->toArray();

        $response = $this->actingAs($user, 'api')->postJson(
            route($this->routePrefix . 'store'),
            $newTask
        );

        // We assert that we get back a status 201:
        // Resource Created for now.
        $response->assertCreated();

        // Assert the table properties contains the factory we made.
        $this->assertDatabaseHas(
            'tasks',
            $newTask
        );
    }

    /** @test */
    public function can_update_a_Task()
    {

        $user = factory(User::class)->create();

        $existingTask = $user->tasks()->create(factory(Task::class)->make()->toArray());

        $newTask = factory(Task::class)->make();

        $response = $this->actingAs($user, 'api')->putJson(
            route($this->routePrefix . 'update', $existingTask),
            $newTask->toArray()
        );

        $response->assertJson([
            'data' => [
                // We keep the ID from the existing Task.
                'id' => $existingTask->id,
                // But making sure the title changed.
                'title' => $newTask->title
            ]
        ]);
    }

    /** @test */
    //can update task status
    public function can_update_task_status()
    {
        $user = factory(User::class)->create();

        $existingTask = $user->tasks()->create(factory(Task::class)->make()->toArray());

        $newTask = factory(Task::class)->make();

        $response = $this->actingAs($user, 'api')->putJson(
            route($this->routePrefix . 'changeStatus', $existingTask),
            $newTask->toArray()
        );

        $response->assertJson([
            'data' => [
                // We keep the ID from the existing Task.
                'id' => $existingTask->id,
                // But making sure the title changed.
                'status' => $newTask->status
            ]
        ]);
    }

    /** @test */
    //attach multieple labels to task
    public function can_attach_multiple_labels_to_task()
    {
        $user = factory(User::class)->create();

        $task = $user->tasks()->create(factory(Task::class)->make()->toArray());

        $labels = factory(Label::class, 3)->create();

        $response = $this->actingAs($user, 'api')->postJson(
            route($this->routePrefix . 'attach', $task),
            [
                'labels' => $labels->pluck('id')->toArray()
            ]
        );

        $response->assertJson([
            // all labels attached to task
            'labels' => $labels->toArray()
        ]);
    }

    /** @test */
    public function a_task_belongs_to_a_author()
    {
        $author = factory(User::class)->create();
        $author->tasks()->create([
            "title" => "Test Task",
            "description" => "Test Description",
            "status" => false,
        ]);

        $this->assertInstanceOf(User::class, $author->tasks->first()->author);
    }
}
