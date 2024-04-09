@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('HOME PAGE') }}</div>

                <div class="card-body">
                    @role('admin|moderator')
                    <a href="{{route('admin.dashboard')}}" class="link-underline-dark">Admin Panel</a>
                    @endrole
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
