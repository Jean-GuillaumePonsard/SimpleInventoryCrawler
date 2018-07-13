<div class="product card col-lg-6 col-md-12" style="display: flex; flex-direction: row;">
    <img style="height: 200px; width: auto;padding: unset;" src="{{$product->product_img_url}}">
    <div class="card-body">
        <h5 class="card-title">{{$product->product_name}}</h5>
        <h6 class="card-text">Price: €{{$product->product_price}}</h6>
        @if($product->is_active == false)
            <small>This product is discontinued and should be removed from your wish list</small>
        @endif
        @auth
            <div style="display: flex; justify-content: center; align-items: flex-end;">
                @if(\Illuminate\Support\Facades\Auth::user()->products->contains($product))
                    @include('products.deleteProductForm', ['productId' => $product->id])
                @else
                    @include('products.addProductForm', ['productId' => $product->id])
                @endif
            </div>
        @endauth
    </div>
</div>
