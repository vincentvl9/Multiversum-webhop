<?php if(!class_exists('raintpl')){exit;}?><!-- <div class="shopHeader">
    <img class="responsive-img" src="https://cdn.pixabay.com/photo/2015/11/19/08/52/banner-1050629__340.jpg">
</div> -->
<br>
<div class="row">
    <div class="shopSearch col s12 m6 l6 offset-m3 offset-l3">
        <form action="" method="GET">
            <label for="searchbar"></label>
            <input type="text" name="searchProduct" placeholder="Zoek in webshop">
        </form>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col s6 m2 l2">
            <h5>Categorieën</h5>
            <hr class="lineShop">
            <ul class="catogoriesList">
                <li class="shopCatogorie"><a href="shop">Alle producten</a></li>
                <?php echo Core::getAllCatogories(); ?>
                <li class="shopCatogorie"><a href="shop?sale=1">Aanbiedingen</a></li>
            </ul>
        </div>
        <div class="products col s6 m10 l10 center">
            <h5>Producten</h5>
            <hr class="lineShop">
            <?php echo Core::getAllProducts(); ?>
        </div>
    </div>
</div>