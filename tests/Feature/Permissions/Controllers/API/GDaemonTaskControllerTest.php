<?php

namespace Tests\Feature\Permissions\Controllers\API;

use Gameap\Models\GdaemonTask;
use Illuminate\Http\Response;
use Tests\Feature\Permissions\PermissionsTestCase;

class GDaemonTaskControllerTest extends PermissionsTestCase
{
    /** @var GdaemonTask */
    private $task;

    public function setUp(): void
    {
        parent::setUp();

        $this->task = factory(GdaemonTask::class)->create();
    }

    public function routesDataProvider()
    {
        return [
            ['get', 'api.gdaemon_tasks.list'],
            ['get', 'api.gdaemon_tasks.get', ['gdaemon_task' => $this->task->id]],
            ['get', 'api.gdaemon_tasks.output', ['gdaemon_task' => $this->task->id]],
        ];
    }

    /**
     * @dataProvider routesDataProvider
     */
    public function testForbidden($method, $route, $params = [], $data = [])
    {
        $this->setCurrentUserRoles(['user']);

        $response = $this->{$method}(route($route, $params), $data);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}