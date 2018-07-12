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
                    Here is your wishlist, you can remove any item you don't want anymore
                </div>
            @endauth
        </div>
    </div>
@endsection


@section('scripts')
    document.addEventListener('DOMContentLoaded', function () {
        actionOnSubmittedForm = function(form)
        {
            console.log($(form).parents('.product.card'));
            $(form).parents('.product.card').replaceWith();
        }
    });
@endsection