@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    @foreach($productsList as $product)
                        @if($product->is_active)
                            @include('products.display', ['product' => $product])
                        @endif
                    @endforeach
                </div>
            </div>
            @auth()
                <div class="col-sm-4">
                    <button class="btn btn-primary" onclick="location.href='{{url('/wishlist')}}'" style="position: fixed;">Go to my Wish list</button>
                </div>
            @endauth
        </div>
    </div>
@endsection

@section('scripts')
    document.addEventListener('DOMContentLoaded', function () {
        actionOnSubmittedForm = function(form) {
            var addProductForm = `@include('products.addProductForm', ['productId' => 0])`;
            var deleteProductForm = `@include('products.deleteProductForm', ['productId' => 0])`;
            var productId = form.find('input[name=product]').val();
            console.log(productId);
            console.log($(form));
            var parent = $(form).parent();
            console.log(parent);
            if ($(form).hasClass('addProduct')) {
                $(form).replaceWith(deleteProductForm);
                parent.find('form').attr('id', 'deleteProduct' + productId).find('input[name=product]').val(productId);
            } else {
                $(form).replaceWith(addProductForm);
                parent.find('form').attr('id', 'addProduct' + productId).find('input[name=product]').val(productId);
            }
        }
    });
@endsection