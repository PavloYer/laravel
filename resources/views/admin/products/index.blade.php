@extends('layouts.admin')

@section('content')

    <div class=card-header>
        <h3 class="text-center fw-semibold">Products</h3>
    </div>
    <hr>

    <div class="container">
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>@sortablelink('id', 'ID')</th>
                <th>@sortablelink('slug', 'Slug')</th>
                <th>@sortablelink('title', 'Title')</th>
                <th>@sortablelink('SKU', 'SKU')</th>
                <th>@sortablelink('category', 'Category')</th>
                <th>@sortablelink('quantity', 'Quantity')</th>
                <th>@sortablelink('finalPrice', 'Price')</th>
                <th>@sortablelink('created_at', 'Created')</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->slug}}</td>
                    <td>{{$product->title}}</td>
                    <td>{{$product->SKU}}</td>
                    <td>-</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->finalPrice}}</td>
                    <td>{{$product->created_at}}</td>
                    <td>
                        <form action="{{route('admin.products.destroy', $product)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="{{route('admin.products.edit', $product)}}" class="btn btn-outline-primary"><i class="fa-solid fa-user-pen"></i></a>
                            <button type="submit" class="btn btn-outline-danger"><i class="fa-regular fa-square-minus"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        {{$products->links()}}
    </div>



@endsection
