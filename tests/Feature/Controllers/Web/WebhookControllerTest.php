<?php

namespace Feature\Controllers\Web;

use App\Models\User;
use App\Repositories\Crm\CustomTelephonyRepository;
use App\Repositories\Crm\TaskRepository;
use Database\Factories\UserFactory;
use Mockery\MockInterface;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();

        $this->mock(CustomTelephonyRepository::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('callStart')->andReturn(['contactId' => rand(100, 10000)]);
            $mock->shouldReceive('callEnd')->andReturn(['contactId' => rand(100, 10000)]);
        });

        $this->mock(TaskRepository::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('create')->andReturnTrue();
        });
    }

    public function testWebhookCallStartIncoming(): void
    {
        $fakeWebhookCall = $this->fakeWebhookCall('call.started', 'incoming');
        $this->user->webhook_token = $fakeWebhookCall['token'];
        $this->user->save();

        $this
            ->postJson('/custom-telephony/webhooks', $fakeWebhookCall)
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);
    }

    public function testWebhookCallEndIncoming(): void
    {
        $fakeWebhookCall = $this->fakeWebhookCall('call.ended', 'incoming', rand(10, 1000));
        $this->user->webhook_token = $fakeWebhookCall['token'];
        $this->user->save();

        $this
            ->postJson('/custom-telephony/webhooks', $fakeWebhookCall)
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);
    }

    public function testWebhookCallStartOutgoing(): void
    {
        $fakeWebhookCall = $this->fakeWebhookCall('call.started', 'outgoing');
        $this->user->webhook_token = $fakeWebhookCall['token'];
        $this->user->save();

        $this
            ->postJson('/custom-telephony/webhooks', $fakeWebhookCall)
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);
    }

    public function testWebhookCallEndOutgoing(): void
    {
        $fakeWebhookCall = $this->fakeWebhookCall('call.ended', 'outgoing', rand(10, 1000));
        $this->user->webhook_token = $fakeWebhookCall['token'];
        $this->user->save();

        $this
            ->postJson('/custom-telephony/webhooks', $fakeWebhookCall)
            ->assertStatus(200)
            ->assertJsonStructure(['result'])
            ->assertJsonPath('result', true);
    }

    private function fakeWebhookCall(string $event, string $type, int $duration = 0): array
    {
        return [
            'call_id' => rand(100, 10000),
            'event' => $event,
            'type' => $type,
            'phone' => fake()->phoneNumber(),
            'duration' => $duration,
            'is_success' => true,
            'token' => md5(rand(100, 10000)),
        ];
    }
}
