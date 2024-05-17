<?php

namespace Tests\Feature\User;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;
use function Webmozart\Assert\Tests\StaticAnalysis\stringWillNotBeRedundantIfAssertingAndNotUsingEither;

class LoginTest extends TestCase
{
    use SetupTrait;

    public function test_allow_see_login_view()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_allow_login_with_valid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->post(route('login'), [
                'email' => $user->email,
                'password' => $user->password
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('home');
    }

    public function test_doesnt_allow_login_with_invalid_data()
    {
        $response = $this->post(route('login'), [
                'email' => 'qwerty@email.com',
                'password' => 'invalid_password'
            ]);

        $response->assertStatus(302);
        $this->assertGuest();
    }

    public function test_allow_see_admin_panel_view_with_role_admin()
    {
        $response = $this->actingAs($this->user())
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_allow_see_admin_panel_view_with_role_moderator()
    {
        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_doesnt_allow_see_admin_panel_view_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_allow_see_admin_categories_view_with_role_admin()
    {
        $response = $this->actingAs($this->user())
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_allow_see_admin_categories_view_with_role_moderator()
    {
        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_doesnt_allow_see_admin_categories_view_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.categories.index'));

        $response->assertForbidden();
    }

    public function test_allow_see_admin_products_view_with_role_admin()
    {
        $response = $this->actingAs($this->user())
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_allow_see_admin_products_view_with_role_moderator()
    {
        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_doesnt_allow_see_admin_products_view_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.products.index'));

        $response->assertForbidden();
    }
}
