<div class="container productInfo">
    <div class="row">
        <div class="col s12 m4 l4">
            <div class="slider">
                <ul class="slides">
            {function="Core::getProductPics()"}
                </ul>
            </div>
        </div>
        <div class="col s12 m5 l5">
            <h3>{$product['0']['product_name']}</h3>
            <div class="fr-view">
                {$product['0']['product_description']}
            </div>
        </div>
        <div class="col offset-m1 offset-l1 s8 m2 l2">
            <h3>Prijs:</h3>
            <h4 class="productPrice">&euro; {$product['0']['product_price']}</h4>
            <a class="productOrder" href="admin-products">Terug</a>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.slider').slider();
    });
</script>