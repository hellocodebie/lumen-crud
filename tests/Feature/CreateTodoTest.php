<?php

use App\Todo;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CreateTodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_create_todo()
    {
        $response = $this->post('/todos', [
            'name' => 'example todo',
            'completed' => false
        ]);

        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(200);
        $this->assertEquals('success', $data['message']);
        tap(Todo::latest('id')->first(), function ($todo) {
            $this->assertEquals('example todo', $todo->name);
        });
    }

    /** @test */
    public function a_todo_name_is_required()
    {
        $response = $this->post('/todos', [
            'name' => '',
        ]);

        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(422);
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('The name field is required.', $data['name'][0]);
    }

    /** @test */
    public function a_todo_name_should_be_a_string()
    {
        $response = $this->post('/todos', [
            'name' => false,
        ]);

        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(422);
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('The name must be a string.', $data['name'][0]);
    }

    /** @test */
    public function a_todo_name_should_not_max_255()
    {
        $response = $this->post('/todos', [
            'name' => str_repeat('a', 256),
        ]);

        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(422);
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('The name may not be greater than 255 characters.', $data['name'][0]);
    }

    /** @test */
    public function a_todo_completed_should_be_a_boolean()
    {
        $response = $this->post('/todos', [
            'name' => 'example todo',
            'completed' => 'this is invalid'
        ]);

        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(422);
        $this->assertArrayHasKey('completed', $data);
        $this->assertEquals('The completed field must be true or false.', $data['completed'][0]);
    }
}
