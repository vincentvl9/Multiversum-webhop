<?php
require_once 'includes/framework.php';
// Inloggen


$varShoppingCart = array(
    "siteTitle" => $siteShort . " &bull; Winkelwagen",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varShoppingCart);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'shoppingcart');
$template->draw($siteTemplate . '_footer');
?>