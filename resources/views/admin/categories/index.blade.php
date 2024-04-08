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
                    <td>
                        <form action="{{route('admin.categories.destroy', $category)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="{{route('admin.categories.edit', $category)}}" class="btn btn-outline-primary"><i class="fa-solid fa-user-pen"></i></a>
                            <button type="submit" class="btn btn-outline-danger"><i class="fa-regular fa-square-minus"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        {{$categories->links()}}
    </div>



@endsection
