@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <form action="{{route('admin.products.update', $product)}}" method="POST"
                      class="d-flex align-items-center justify-content-center">
                    <div class="card w-50">
                        <div class="card-header text-center">
                            <h3>Update product</h3>
                        </div>
                        <div class="card-body">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                           class="form-control @error('title') is-invalid @enderror" name="title"
                                           value="{{ old('title') ?? $product->title}}" required>

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="SKU" class="col-md-4 col-form-label text-md-end">{{ __('SKU') }}</label>

                                <div class="col-md-6">
                                    <input id="SKU" type="number" minlength="10" maxlength="11"
                                           class="form-control @error('SKU') is-invalid @enderror" name="SKU"
                                           value="{{ old('SKU') ?? $product->SKU}}" required>

                                    @error('SKU')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="categories"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Categories') }}</label>

                                <div class="col-md-6">
                                    <select name="categories[]" id="categories"
                                            class="form-control @error('categories') is-invalid @enderror" multiple>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{$category->id}}"
                                                @if(in_array($category->id, $productCategories)) selected @endif
                                            >{{$category->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('categories')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price') }}</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" step="any" min="1"
                                           class="form-control @error('price') is-invalid @enderror" name="price"
                                           value="{{ old('price') ?? $product->price}}" required>

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="new_price"
                                       class="col-md-4 col-form-label text-md-end">{{ __('New Price') }}</label>

                                <div class="col-md-6">
                                    <input id="new_price" type="number" step="any" min="1"
                                           class="form-control @error('new_price') is-invalid @enderror"
                                           name="new_price"
                                           value="{{ old('new_price') ?? $product->new_price}}">

                                    @error('new_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="quantity"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Quantity') }}</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number"
                                           class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                           value="{{ old('quantity') ?? $product->quantity}}" min="0" required>

                                    @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Description') }}
                                </label>

                                <div class="col-md-6">
                                    <textarea class="w-100" id="description" name="description"
                                    >{{old('description') ?? ($product->description ?? '')}}
                                    </textarea>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
