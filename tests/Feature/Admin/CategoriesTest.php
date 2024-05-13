<?php

namespace Tests\Feature\Admin;

use App\Enums\Roles;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use SetupTrait;

    public function test_allow_see_categories_with_role_admin()
    {
        $categories = Category::factory(2)->create();

        $response = $this->actingAs($this->user())
            ->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories->pluck('name')->toArray());
    }

    public function test_allow_see_categories_with_role_moderator()
    {
        $categories = Category::all()->pluck('name')->toArray();

        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories);
    }

    public function test_doesnt_allow_see_categories_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.categories.index'));

        $response->assertForbidden();
    }

    public function test_allow_see_create_categories_view_with_role_admin()
    {
        $response = $this->actingAs($this->user())
            ->get(route('admin.categories.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.create');
    }

    public function test_allow_see_create_categories_view_with_role_moderator()
    {
        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.categories.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.create');
    }

    public function test_doesnt_allow_see_create_categories_view_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.categories.create'));

        $response->assertForbidden();
    }

    public function test_create_categories_with_valid_data()
    {
        $category = Category::factory()->make()->toArray();

        $this->assertDatabaseMissing(Category::class, [
            'name' => $category['name']
        ]);

        $response = $this->actingAs($this->user())
            ->get(route('admin.categories.store', $category));

        $response->assertStatus(302); //why I get status:200 response?
        $response->assertRedirectToRoute('admin.categories.index');

        $this->assertDatabaseHas(Category::class, [
            'name' => $category['name']
        ]);

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($category) {
            return $collection->first()['message'] == "Category $category[name] was created.";
        });

    }
}
