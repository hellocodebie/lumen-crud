<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class EditTodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_edit_a_todo()
    {
        $todo = factory(\App\Todo::class)->create([
            'name' => 'example todo',
            'completed' => false
        ]);

        $response = $this->put("/todos/{$todo->id}", [
            'name' => 'updated todo',
            'completed' => true
        ]);

        $response->assertResponseStatus(200);
        tap(\App\Todo::latest('id')->first(), function ($todo) {
            $this->assertEquals('updated todo', $todo->name);
            $this->assertEquals(true, $todo->completed);
        });
    }
}
