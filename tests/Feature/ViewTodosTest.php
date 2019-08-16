<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ViewTodosTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_list_of_todos()
    {
        factory(\App\Todo::class, 5)->create();

        $response = $this->get('/todos');

        $data = json_decode((string) $response->response->getContent(), true);

        $response->assertResponseStatus(200);
        $this->assertEquals(5, count($data));
    }
}
