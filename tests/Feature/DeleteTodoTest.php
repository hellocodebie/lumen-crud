<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class DeleteTodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_delete_a_todo()
    {
        $todo = factory(\App\Todo::class)->create();

        $response = $this->delete("/todos/{$todo->id}");

        $response->assertResponseStatus(204);
    }
}
