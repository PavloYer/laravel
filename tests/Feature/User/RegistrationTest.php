<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use SetupTrait;

    public function test_allow_see_registration_view()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_allow_register_with_valid_data()
    {
        $user = User::factory()->make();

        $response = $this->post(route('register'), [...$user->toArray(),
            'password' => 'dgfrt4hg',
            'password_confirmation' => 'dgfrt4hg'
        ]);

        $this->assertDatabaseHas(User::class, [
            'email' => $user->email
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('home');
        $this->assertAuthenticated();
    }

    public function test_doesnt_allow_register_with_invalid_data()
    {
        $user = User::factory()->make();

        $response = $this->post(route('register'), [...$user->toArray(),
            'password' => 'dt4hg',
            'password_confirmation' => 'dt4hg'
        ]);

        $this->assertDatabaseMissing(User::class, [
            'email' => $user->email
        ]);

        $response->assertStatus(302);
    }
}
