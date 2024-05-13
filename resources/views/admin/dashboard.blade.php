@extends('layouts.admin')

@section('content')

    <table class="table table-dark table-striped w-75 m-auto">
        <thead>
        <th>Role</th>
        <th>Users</th>
        @role('admin')
            <th>Action</th>
        @endrole
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ucfirst($role->name)}}</td>
                <td>{{$role->users()->count()}}</td>
                @role('admin')
                    <th>Action</th>
                @endrole
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>

    <table class="table table-dark table-striped w-75 m-auto">
        <thead>
        <th>Item</th>
        <th>Number</th>
        </thead>
        <tbody>
            <tr>
                <td><a href="{{route('admin.categories.index')}}">Categories</a></td>
                <td>{{$categories->count()}}</td>
            </tr>
            <tr>
                <td><a href="{{route('admin.products.index')}}">Products</a></td>
                <td>{{$products->count()}}</td>
            </tr>
        </tbody>
    </table>

@endsection
