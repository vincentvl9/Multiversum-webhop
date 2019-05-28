<?php
require_once 'includes/framework.php';
// Inloggen


if(isset($_GET['id'])){
    Core::addToCart(intval($_GET['id']));
}

$varShoppingCartAdd = array(
    "siteTitle" => $siteShort . " &bull; Support",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varShoppingCartAdd);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'shopping-cart-add');
$template->draw($siteTemplate . '_footer');
?>