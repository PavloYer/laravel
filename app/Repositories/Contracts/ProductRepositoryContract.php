<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Models\Product;

interface ProductRepositoryContract
{

    public function create(CreateRequest $request): Product | false;

    public function update(Product $product, UpdateRequest $request): bool;
}
