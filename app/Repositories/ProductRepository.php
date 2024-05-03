<?php

namespace App\Repositories;

use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;

class ProductRepository implements Contracts\ProductRepositoryContract
{

    public function create(CreateRequest $request): Product|false
    {
        try {
            DB::beginTransaction();
            $data = $this->formRequestData($request);
            $product = Product::create($data['attributes']);

            $this->setProductData($product, $data);
            DB::commit();

            return $product;
        } catch (Exception $exception) {
            DB::rollBack();
            logs()->error($exception);
            return false;
        }
    }

    public function update(Product $product, UpdateRequest $request): bool
    {
        try {
            DB::beginTransaction();
            $data = $this->formRequestData($request);
            $product->update($data['attributes']);

            $this->setProductData($product, $data);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            logs()->error($exception);
            return false;
        }
    }

    protected function setProductData(Product $product, array $data): void
    {
        $product->category()->sync($data['categories']);
    }

    protected function formRequestData(CreateRequest|UpdateRequest $request): array
    {
        return [
            'attributes' => collect($request->validated())
                ->except(['categories'])
                ->merge([
                    'slug' => Str::slug($request->get('title')),
                    'thumbnail' => 'thumbnail'])
                ->toArray(),
            'categories' => $request->get('categories')
        ];
    }
}
