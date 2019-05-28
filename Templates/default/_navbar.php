<div class="topNav">
    <ul class="right-align">
        <?php
        if(isset($_COOKIE['role']) AND $_COOKIE['role'] > 0){
            ?>
            <li><i class="material-icons">grade</i><a href="admin-dashboard">Admin</a></li>
            <?php
        }
        ?>
        <?php
        if (!isset($_SESSION['user'])) {
            ?>
            <li><i class="material-icons">account_circle</i><a href="login">Beheer</a></li>
            <?php
        }else {
            ?>
<!--            <li><i class="material-icons">grade</i><a href="dashboard">Dashboard</a></li>-->
            <li><i class="material-icons">exit_to_app</i><a href="logout">Log uit</a></li>
            <?php
        }
        ?>
        <li><i class="material-icons">help</i><a href="contact">Contact</a></li>
    </ul>
</div>
<nav>
    <div class="nav-wrapper">
        <img class="brand-logo" src="Templates/default/web-gallary/assets/images/logo2.png" alt="">
        <ul class="right">
            <li><a href="index">Home</a></li>
            <li><a href="shop">Shop</a></li>
            <li><a href="shoppingcart"><i class="fas fa-shopping-cart"></i>({function="Core::cartItems()"})</a></li>
        </ul>
    </div>
</nav>