<div class="product card" style="width: 18rem">
    <img class="card-img-top" src="{{$product->d_img_url}}">
    <div class="card-body">
        <h5 class="card-title">{{$product->d_name}}</h5>
        @auth
            <button class="btn btn-primary">ADD TO WISHLIST</button>
        @endauth
    </div>
</div>