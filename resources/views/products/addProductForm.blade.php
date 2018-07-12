<form id="addProduct{{$productId}}" class="addProduct" action="{{url('/wishlist/product')}}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="product" value="{{$productId}}">
    <button class="btn btn-primary" type="submit">ADD TO WISHLIST</button>
</form>