<?php

namespace Feature\Controllers\Web;

use App\Repositories\CustomTelephony\CallRepository;
use Database\Factories\UserFactory;
use Mockery\MockInterface;
use Tests\TestCase;

class CallControllerTest extends TestCase
{
    public function testCall()
    {
        $this->mock(CallRepository::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('call');
        });

        $fakeUser = UserFactory::new()->create();

        $this
            ->postJson('/custom-telephony/call', [
                'integrationId' => $fakeUser->integration_id,
                'phone' => fake()->phoneNumber(),
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);
    }

    public function testDontCall(): void
    {
        $this
            ->postJson('/custom-telephony/call', [
                'integrationId' => -1,
                'phone' => fake()->phoneNumber(),
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', false);
    }
}
