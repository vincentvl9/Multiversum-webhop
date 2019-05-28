<?php if(!class_exists('raintpl')){exit;}?><div class="container productInfo">
    <div class="row">
        <div class="col s12 m4 l4">
            <div class="slider">
                <ul class="slides">
            <?php echo Core::getProductPics(); ?>
                </ul>
            </div>
        </div>
        <div class="col s12 m5 l5">
            <h3><?php echo $product['0']['product_name'];?></h3>
            <div class="fr-view">
                <?php echo $product['0']['product_description'];?>
            </div>
        </div>
        <div class="col offset-m1 offset-l1 s8 m2 l2">
            <h3>Prijs:</h3>
            <h4 class="productPrice">&euro; <?php echo $product['0']['product_price'];?></h4>
            <a class="productOrder" href="admin-products">Terug</a>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.slider').slider();
    });
</script>