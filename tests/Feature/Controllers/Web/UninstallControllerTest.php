<?php

namespace Feature\Controllers\Web;

use Database\Factories\UserFactory;
use Tests\TestCase;

class UninstallControllerTest extends TestCase
{
    public function testUninstall()
    {
        $fakeCredentials = UserFactory::new()->create();

        $this
            ->postJson('/uninstall', [
                'user_id' => $fakeCredentials->sp_user_id,
                'client_id' => $fakeCredentials->sp_client_id,
                'client_secret' => $fakeCredentials->sp_client_secret,
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);

        $this->assertDatabaseEmpty('users');
    }
}
