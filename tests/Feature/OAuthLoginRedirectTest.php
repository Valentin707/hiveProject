<?php

namespace Tests\Feature;

use App\Models\Passport\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OAuthLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_resumes_a_first_party_oauth_authorization_request(): void
    {
        config()->set('session.driver', 'database');

        $user = User::factory()->create([
            'email' => 'operator@hive.test',
            'password' => 'secret-password',
        ]);

        $client = Client::query()->create([
            'name' => 'HiveTasks',
            'secret' => 'client-secret',
            'provider' => null,
            'redirect_uris' => ['https://tasks.hive.test/auth/callback'],
            'grant_types' => ['authorization_code', 'refresh_token'],
            'revoked' => false,
        ]);

        $authorizationUrl = '/oauth/authorize?'.http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => 'https://tasks.hive.test/auth/callback',
            'response_type' => 'code',
            'state' => 'hivetasks-auth-state',
        ]);

        $this->get($authorizationUrl)
            ->assertRedirect(route('login'))
            ->assertSessionHas('url.intended', url($authorizationUrl));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(url($authorizationUrl));

        $authorizationResponse = $this->get($authorizationUrl);
        $callbackUrl = $authorizationResponse->headers->get('Location');

        $this->assertStringStartsWith('https://tasks.hive.test/auth/callback?', $callbackUrl);

        parse_str((string) parse_url($callbackUrl, PHP_URL_QUERY), $callbackParameters);

        $this->assertSame('hivetasks-auth-state', $callbackParameters['state'] ?? null);
        $this->assertNotEmpty($callbackParameters['code'] ?? null);
    }
}
