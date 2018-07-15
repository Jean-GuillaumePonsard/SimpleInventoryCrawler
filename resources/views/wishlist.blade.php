@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    @foreach($productsList as $product)
                        @include('products.display', ['product' => $product])
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                @auth()
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Your wish list</h4>
                            <p>
                                Welcome {{ \Illuminate\Support\Facades\Auth::user()->name }},
                                <br>
                                Here is your wishlist, you can consult your list and remove any item you don't want anymore.
                            </p>
                            <div style="display: flex;align-items: center;justify-content: center">
                                <button class="btn btn-primary" onclick="location.href='{{url('/home')}}'">Go back to homepage</button>
                            </div>
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
        actionOnSubmittedForm = function(form)
        {
            $(form).parents('.product.card').replaceWith();
        }
    });
@endsection