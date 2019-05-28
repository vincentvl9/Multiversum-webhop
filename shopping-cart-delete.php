<?php
require_once 'includes/framework.php';
// Inloggen


Core::removeCart();
$varShoppingCartDelete = array(
    "siteTitle" => $siteShort . " &bull; Winkelwagen",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varShoppingCartDelete);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'shoppingcart');
$template->draw($siteTemplate . '_footer');
?>