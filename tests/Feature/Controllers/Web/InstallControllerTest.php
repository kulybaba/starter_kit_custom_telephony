<?php

namespace Feature\Controllers\Web;

use App\Repositories\Crm\BoardRepository;
use App\Repositories\Crm\CustomTelephonyRepository;
use App\Repositories\CustomTelephony\WebhookRepository;
use App\Repositories\SendPulse\CredentialRepository;
use Database\Factories\UserFactory;
use Mockery\MockInterface;
use Tests\TestCase;

class InstallControllerTest extends TestCase
{
    public function testInstall()
    {
        $integrationId = rand(1, 100000);
        $fakeCredentials = UserFactory::new()->make();
        $webhookToken = md5(rand(100, 10000));

        $this->mock(CredentialRepository::class, static function (MockInterface $mock) use ($fakeCredentials) {
            $mock->shouldReceive('getByCode')->andReturn([
                'user_id' => $fakeCredentials['sp_user_id'],
                'client_id' => $fakeCredentials['sp_client_id'],
                'client_secret' => $fakeCredentials['sp_client_secret'],
            ]);
        });

        $this->mock(CustomTelephonyRepository::class, static function (MockInterface $mock) use ($integrationId) {
            $mock->shouldReceive('connectCustomTelephony')->andReturn($integrationId);
        });

        $this->mock(WebhookRepository::class, static function (MockInterface $mock) use ($webhookToken) {
            $mock->shouldReceive('getToken')->andReturn($webhookToken);
        });

        $this->mock(BoardRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAll')->andReturn([
                $this->fakeBoard(),
                $this->fakeBoard(),
                $this->fakeBoard(),
            ]);
        });

        $this
            ->post('/install?code=' . fake()->md5())
            ->assertStatus(302)
            ->assertHeader('Content-Type', 'text/html; charset=utf-8');;

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'sp_user_id' => $fakeCredentials->sp_user_id,
            'sp_client_id' => $fakeCredentials->sp_client_id,
            'sp_client_secret' => $fakeCredentials->sp_client_secret,
            'integration_id' => $integrationId,
            'webhook_token' => $webhookToken,
        ]);
    }

    public function testDontInstall()
    {
        $this
            ->postJson('/install?code=')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonPath('errors.code.0', 'The code field is required.');

        $this->assertDatabaseEmpty('users');
    }

    private function fakeBoard(): array
    {
        $id = rand(100, 100000);

        return [
            'id' => $id,
            'name' => fake()->word,
            'userId' => rand(100, 100000),
            'order' => rand(1, 10),
            'steps' => [
                $this->fakeBoardStep($id),
                $this->fakeBoardStep($id),
                $this->fakeBoardStep($id),
            ],
        ];
    }

    private function fakeBoardStep(int $boardId): array
    {
        return [
            'id' => rand(100, 100000),
            'name' => fake()->word,
            'color' => fake()->hexColor(),
            'order' => rand(1, 10),
            'boardId' => $boardId,
            'addEndHours' => null,
            'notifyIn' => null,
        ];
    }
}
