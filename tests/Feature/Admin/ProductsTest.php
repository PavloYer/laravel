<?php

namespace Tests\Feature\Admin;

use App\Enums\Roles;
use App\Models\Product;
use App\Services\FileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\Feature\Traits\SetupTrait;
use Tests\TestCase;
use function Webmozart\Assert\Tests\StaticAnalysis\object;

class ProductsTest extends TestCase
{
    use SetupTrait;

    public function test_allow_see_products_with_role_admin()
    {
//        $product = $this->createProduct('test.png');

        $response = $this->actingAs($this->user())
            ->get(route('admin.products.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.products.index');
//        $response->assertSeeInOrder([$product['title']]);
    }

    public function test_allow_see_create_products_view_with_role_admin()
    {
        $response = $this->actingAs($this->user())
            ->get(route('admin.products.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.products.create');
    }

    public function test_allow_see_create_products_view_with_role_moderator()
    {
        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.products.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.products.create');
    }

    public function test_doesnt_allow_see_create_products_view_with_role_customer()
    {
        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.products.create'));

        $response->assertForbidden();
    }

    public function test_create_products()
    {
        $data = $this->createProduct('test.png');
        $this->mockFileService($data['slug'], 'test.png');

        $response = $this->actingAs($this->user())
            ->post(route('admin.products.store'), $data);

        $this->assertDatabaseHas(Product::class, [
            'title' => $data['title'],
            'thumbnail' => "$data[slug]/test.png"
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.products.index');

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($data) {
            return $collection->first()['message'] == "Product '$data[title]' was created";
        });
    }

    public function test_allow_see_edit_products_view_with_role_admin()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user())
            ->get(route('admin.products.edit', $product));

        $response->assertSuccessful();
        $response->assertViewIs('admin.products.edit');
    }

    public function test_allow_see_edit_products_view_with_role_moderator()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user(Roles::MODERATOR))
            ->get(route('admin.products.edit', $product));

        $response->assertSuccessful();
        $response->assertViewIs('admin.products.edit');
    }

    public function test_doesnt_allow_see_edit_products_view_with_role_customer()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user(Roles::CUSTOMER))
            ->get(route('admin.products.edit', $product));

        $response->assertForbidden();
    }

    public function test_update_products()
    {
        $product = Product::factory()->create();

        $data = $this->createProduct('test.png');
        $this->mockFileService($data['slug'], 'test.png');

//        $this->mock(FileService::class, function (MockInterface $mock) use ($product) {
//            $mock->shouldReceive('remove');
//        });


        $response = $this->actingAs($this->user())
            ->put(route('admin.products.update', $product), $data);

        $product->refresh();

        $this->assertDatabaseHas(Product::class, [
            'title' => $data['title'],
            'thumbnail' => "$data[slug]/test.png"
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.products.index');

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($data) {
            return $collection->first()['message'] == "Product '$data[title]' was updated";
        });
    }

    public function test_delete_existing_products()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user())
            ->delete(route('admin.products.destroy', $product), $product->toArray());

        $this->assertDatabaseMissing(Product::class, [
            'title' => $product['title'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.products.index');

        $response->assertSessionHas('toasts');
        $response->assertSessionHas('toasts', function ($collection) use ($product) {
            return $collection->first()['message'] == "Product '$product[title]' was deleted";
        });
    }

    public function test_doesnt_delete_not_existing_products()
    {
        $product = Product::factory()->create();
        $product->id = -1;

        $response = $this->actingAs($this->user())
            ->delete(route('admin.products.destroy', $product), $product->toArray());

        $this->assertDatabaseHas(Product::class, [
            'title' => $product['title'],
        ]);

        $response->assertStatus(404);
    }

    protected function createProduct(string $imagePath): array
    {
        $image = UploadedFile::fake()->image($imagePath);

        $data = array_merge(Product::factory()->make()->toArray(),
            ['thumbnail' => $image]);
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }

    protected function mockFileService(string $filePath, string $fileName)
    {
        $this->mock(FileService::class, function (MockInterface $mock) use ($filePath, $fileName) {
            $mock->shouldReceive('upload')
                ->andReturn("$filePath/$fileName");
        });
    }
}
