@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    @foreach($productsList as $product)
                        @include('products.display', ['product' => $product])
                    @endforeach
                </div>
            </div>
            @auth()
                <div class="col-sm-4">
                    I am logged in
                </div>
            @endauth
        </div>
    </div>
@endsection
