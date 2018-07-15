@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    @foreach($productsList as $product)
                        @if($product->is_active)
                            @include('products.display', ['product' => $product])
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-md-2">
                @auth()
                    <div class="card"  style="position: fixed;">
                        <div class="card-body">
                            <p>You can check your wish list by clicking the following button:</p>
                            <div style="display: flex; justify-content: center; align-items: flex-end;">
                                <button class="btn btn-primary" onclick="location.href='{{url('/wishlist')}}'">Go to my Wish list</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">You must login or register to add a product to your wish list</h4>
                            <p>To add any of these item into a wish list, you must login to your account or create an new account.</p>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('products.ajaxFormScript')

    document.addEventListener('DOMContentLoaded', function () {
        actionOnSubmittedForm = function(form) {
            var addProductForm = `@include('products.addProductForm', ['productId' => 0])`;
            var deleteProductForm = `@include('products.deleteProductForm', ['productId' => 0])`;
            var productId = form.find('input[name=product]').val();
            var parent = $(form).parent();

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