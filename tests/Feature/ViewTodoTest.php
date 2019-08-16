<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ViewTodoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_todo()
    {
        $todo = factory(\App\Todo::class)->create(['name' => 'example todo']);

        $response = $this->get("/todos/{$todo->id}");
        $data = json_decode((string) $response->response->content(), true);

        $response->assertResponseStatus(200);
        $this->assertEquals('example todo', $data['name']);
    }

    /** @test */
    public function a_user_should_see_404_not_found()
    {
        $response = $this->get("/todos/1234");

        $data = json_decode((string) $response->response->getContent(), true);

        $response->assertResponseStatus(404);
        $this->assertEquals('Unable to find todo', $data['error']);
    }
}
