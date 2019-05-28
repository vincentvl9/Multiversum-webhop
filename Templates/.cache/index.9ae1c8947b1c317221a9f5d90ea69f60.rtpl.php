<?php if(!class_exists('raintpl')){exit;}?><div class="container">
    <br><br>
    <h1 class="header center multiversumHeader">Multiversum</h1>
    <div class="row center">
        <h5 class="header col s12 light">De plek waar jij je VR Bril kan halen.</h5>
    </div>
</div>
<div class="container cards">
    <div class="row">
        <div class="col m8 s12 card">
            <img class="vrHeader" src="Templates/default/web-gallary/assets/images/vrheader.jpg" alt="">
            <a href="shop" class="card-title">Alle VR-Brillen</a>
        </div>
        <div class="col m4 s12 card">
            <a href=""><img class="vrHeader" src="Templates/default/web-gallary/assets/images/trust.jpg" alt=""></a>
            <a class="card-title" href="shop">Trust VR</a>

        </div>
    </div>
    <div class="row catoRow">
        <div class="row center">
            <h5 class="header col s12 light">Aanbiedingen</h5>
        </div>
        <div class="products col s6 m10 l10 center">    
            <?php echo Core::showSale(); ?>
        </div>
    </div>
    <div class="row catoRow">
        <div class="col m3 s12 card">
            <!-- <a href=""><img class="vrHeader" src="Templates/default/web-gallary/assets/images/playstation.jpg" alt=""></a> -->
            <a class="card-title" href="shop">Playstation VR</a>
        </div>
        <div class="col m3 s12 card">
            <!-- <a href=""><img class="vrHeader" src="Templates/default/web-gallary/assets/images/oclusrift.jpg" alt=""></a> -->
            <a class="card-title" href="shop">Oculus Rift</a>
        </div>
        <div class="col m3 s12 card">
            <!-- <a href=""><img class="vrHeader" src="Templates/default/web-gallary/assets/images/samsung.jpg" alt=""></a> -->
            <a class="card-title" href="shop">Samsung VR</a>
        </div>
        <div class="col m3 s12 card">
            <!-- <a href=""><img class="vrHeader" src="Templates/default/web-gallary/assets/images/htcvive.jpg" alt=""></a> -->
            <a class="card-title" href="shop">HTC Vive</a>
        </div>
    </div>
</div>