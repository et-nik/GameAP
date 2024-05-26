<?php

namespace Tests\Feature\Permissions\Controllers\API;

use Gameap\Models\GameMod;
use Illuminate\Http\Response;
use Tests\Feature\Permissions\PermissionsTestCase;

class GameModsControllerTest extends PermissionsTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function routesDataProvider()
    {
        /** @var GameMod $gameMod */
        $gameMod = factory(GameMod::class)->create();

        return [
            ['get', 'api.game_mods'],
            ['get', 'api.game_mods.get_mods_list', $gameMod->game_code],
            ['post', 'api.game_mods.store'],
            ['get', 'api.game_mods.show', $gameMod->id],
            ['put', 'api.game_mods.update', $gameMod->id],
            ['delete', 'api.game_mods.destroy', $gameMod->id],
        ];
    }

    /**
     * @dataProvider routesDataProvider
     */
    public function testForbidden($method, $route, $param = null, $data = [])
    {
        $this->setCurrentUserRoles(['user']);

        $response = $this->{$method}(route($route, $param), $data);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}