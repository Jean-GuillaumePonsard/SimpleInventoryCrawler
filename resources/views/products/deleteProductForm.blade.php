<form id="deleteProduct{{$productId}}" class="deleteProduct" action="{{url('/wishlist/product')}}" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <input type="hidden" name="product" value="{{$productId}}">
    <button class="btn btn-danger" type="submit">DELETE FROM WISHLIST</button>
</form>