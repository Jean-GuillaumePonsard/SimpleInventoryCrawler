<div class="product card col-sm-6" style="display: flex; flex-direction: row;">
    <img style="height: 200px; width: auto;padding: unset;" src="{{$product->d_img_url}}">
    <div class="card-body">
        <h5 class="card-title">{{$product->d_name}}</h5>
        @if($product->is_active == false)
            <small>This product is discontinued and should be removed from your wish list</small>
        @endif
        @auth
            @if(\Illuminate\Support\Facades\Auth::user()->products->contains($product))
                @include('products.deleteProductForm', ['productId' => $product->id])
            @else
                @include('products.addProductForm', ['productId' => $product->id])
            @endif
        @endauth
    </div>
</div>
