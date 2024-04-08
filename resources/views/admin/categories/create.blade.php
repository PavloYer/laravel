@extends('layouts.admin')

@section('content')

    <div class=card-header>
        <h3 class="text-center fw-semibold">Categories</h3>
    </div>
    <hr>

    <div class="container">
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Usage</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>{{$category->parent->name ?? '-'}}</td>
                    <td>{{$category->product_count}}</td>
                    <td>r-</td>
                </tr>
            @endforeach

            </tbody>
        </table>

        {{$categories->links()}}
    </div>



@endsection
