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
            ->post(route('admin.categories.store', $category));

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.categories.index');

        $this->assertDatabaseHas(Category::class, [
            'name' => $category['name']
        ]);

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($category) {
            return $collection->first()['message'] == "Category $category[name] was created.";
        });
    }

    public function test_doesnt_create_categories_with_invalid_data()
    {
        $category = ['name' => 'qw'];

        $this->assertDatabaseMissing(Category::class, [
            'name' => $category['name']
        ]);

        $response = $this->actingAs($this->user())
            ->post(route('admin.categories.store', $category));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_allow_see_update_categories_view_with_role_admin()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user())
            ->get(route('admin.categories.edit', $category));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.edit');
    }

    public function test_allow_see_update_categories_view_with_role_moderator()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.categories.edit', $category));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.edit');
    }

    public function test_doesnt_allow_see_update_categories_view_with_role_customer()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.categories.edit', $category));

        $response->assertForbidden();
    }

    public function test_update_categories_with_valid_data()
    {
        $category = Category::factory()->create();
        $parent = Category::factory()->create();

        $response = $this->actingAs($this->user())
            ->put(route('admin.categories.update', $category), [
                'name' => $category->name,
                'parent_id' => $parent->id
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.categories.index');

        $this->assertDatabaseHas(Category::class, [
            'name' => $category['name']
        ]);

        $category->refresh();
        $this->assertEquals($parent->id, $category->parent_id);

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($category) {
            return $collection->first()['message'] == "Category $category[name] was updated.";
        });
    }

    public function test_doesnt_update_categories_with_invalid_name_data()
    {
        $category = Category::factory()->create();

        $invalid_data = [
            'name' => 'er',
            'parent_id' => -1
        ];

        $response = $this->actingAs($this->user())
            ->put(route('admin.categories.update', $category), [
                'name' => $invalid_data['name'],
                'parent_id' => $invalid_data['parent_id']
            ]);

        $response->assertStatus(302);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors(['name', 'parent_id']);
    }

    public function test_allow_remove_existing_category()
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas(Category::class, [
                'id' => $category['id']
            ]
        );

        $response = $this->actingAs($this->user())
            ->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.categories.index');
        $this->assertDatabaseMissing(Category::class, [
            'id' => $category->id
        ]);

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($category) {
            return $collection->first()['message'] == "Category $category->name was removed.";
        });
    }

    public function test_doesnt_allow_remove_not_existing_category()
    {
        $category = Category::factory()->create();
        $category->id = -1;

        $response = $this->actingAs($this->user())
            ->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(404);
        $this->assertDatabaseHas(Category::class,
            ['name' => $category->name]);
    }
}
