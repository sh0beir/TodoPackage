<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use sh0beir\todo\Models\Label;
use sh0beir\todo\Models\Task;
use sh0beir\todo\Tests\TestCase;

class LabelsTest extends TestCase
{
    use RefreshDatabase;

    private string $routePrefix = 'api.labels.';

    /** @test */
    public function indexLabels()
    {
        $user = factory(User::class)->create();
        $label = factory(Label::class)->create();

        $response = $this->actingAs($user, 'api')->getJson(route($this->routePrefix . 'index'));
        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'id' => $label->id,
                    'label' => $label->label,
                    'total_tasks' => $label->tasks()->count(),
                ]
            ]
        ]);
    }

    /** @test */
    public function storeLabel()
    {
        // Build a non-persisted Label factory model.
        $user = factory(User::class)->create();
        $newLabel = factory(Label::class)->make();

        $response = $this->actingAs($user, 'api')->postJson(
            route($this->routePrefix . 'store'),
            $newLabel->toArray()
        );

        // $response->assertJsonValidationErrors(['label']);
        // We assert that we get back a status 201:
        // Resource Created for now.
        $response->assertCreated();

        // Assert the table properties contains the factory we made.
        $this->assertDatabaseHas(
            'labels',
            $newLabel->toArray()
        );
    }
}
