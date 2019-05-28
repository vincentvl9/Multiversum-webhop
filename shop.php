<?php
require_once 'includes/framework.php';

$varShop = array(
    "siteTitle" => $siteShort . " &bull; Webshop",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varShop);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'shop');
$template->draw($siteTemplate . '_footer');
?>