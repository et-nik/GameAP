<?php

namespace Tests\Feature\Permissions\Controllers\API;

use Gameap\Models\Game;
use Illuminate\Http\Response;
use Tests\Feature\Permissions\PermissionsTestCase;

class GamesControllerTest extends PermissionsTestCase
{
    /** @var Game */
    private $game;

    public function setUp(): void
    {
        parent::setUp();

        $this->game = factory(Game::class)->create();
    }

    public function routesDataProvider()
    {
        return [
            ['get', 'api.games', []],
            ['post', 'api.games.store', []],
            ['get', 'api.games.show', ['game' => $this->game->code]],
            ['put', 'api.games.update', ['game' => $this->game->code]],
            ['post', 'api.games.upgrade', []],
            ['get', 'api.games.mods', ['game' => $this->game->code]],
            ['delete', 'api.games.destroy', ['game' => $this->game->code]],
        ];
    }

    /**
     * @dataProvider routesDataProvider
     *
     * @param string $method
     * @param string $route
     * @param array $params
     */
    public function testForbidden(string $method, string $route, array $params = [])
    {
        $this->setCurrentUserRoles(['user']);

        $response = $this->{$method}(route($route, $params), []);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}