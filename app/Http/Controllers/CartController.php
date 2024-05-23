<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::instance('cart');

        return view('cart/index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        Cart::instance('cart')
            ->add($product->id, $product->title, 1, $product->finalPrice)
            ->associate(Product::class);

        notify("Product [$product->title] was added to cart");

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'rowId' => ['required', 'string']
        ]);

        Cart::instance('cart')->remove($data['rowId']);
    }

    public function count(Request $request, Product $product)
    {
        $data = $request->validate([
            'rowId' => ['required', 'string'],
            'count' => ['required', 'numeric', 'min:1', 'max:' . $product->quantity]
        ]);

        Cart::instance('cart')->update($data['rowId'], $data['count']);


        notify()->success("Quantity of product [$product->title] was updated");

        return redirect()->back();
    }
}
