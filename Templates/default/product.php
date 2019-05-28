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
            <h3>{$product_name}</h3>
            <div class="fr-view">
                {$product_description}
            </div>
        </div>
        <div class="col offset-m1 offset-l1 s8 m2 l2">
            <h3>Prijs:</h3>
            <h4 class="productPrice">&euro; {$totaal}</h4>
            <a class="productOrder" href="#">Bestel nu</a>
            <a class="productOrder" href="#"><i class="fas fa-cart-plus"></i></a>
        </div>
    </div>
</div>
<div class="container">
    <h4 class="center">Schrijf een review</h4>
    <div class="row">
        <div class="col s12 m8 l8 offset-m2 offset-l2 center">
            <div class="reviews">
                <form method="post" action="">
                    <div class="row">

                    </div>
                    <div class="row">
                        <textarea id="textarea2" class="materialize-textarea" placeholder="Schrijf hier uw review"></textarea>
                    </div>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.slider').slider();
    });
</script>
