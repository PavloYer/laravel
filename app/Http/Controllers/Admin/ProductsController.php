<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryContract;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->sortable()
            ->paginate(10);

        return view('admin/products/index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/products/create', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request, ProductRepositoryContract $repository)
    {
        if ($result = $repository->create($request)) {
            notify()->success("Product '$result->title' was created");
            return redirect()->route('admin.products.index');
        }

        notify()->danger("Error");
        return redirect()->back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['images', 'category']);

        $categories = Category::all();
        $productCategories = $product->category->pluck('id')->toArray();

        return view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'productCategories' => $productCategories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product, ProductRepositoryContract $repository)
    {
        if ($repository->update($product, $request)) {
            notify()->success("Product '$product->title' was updated");
            return redirect()->route('admin.products.index');
        }

        notify()->danger("Error");
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $title = $product->title;
        $product->category()->detach();
        $product->delete();

        notify()->success("Product '$title' was deleted");
        return redirect()->route('admin.products.index');
    }
}
