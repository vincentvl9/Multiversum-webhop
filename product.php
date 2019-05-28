<?php
require_once 'includes/framework.php';
// Inloggen

$product = Core::getSingleProduct();


// Product informatie
$product_name = $product[0]['product_name'];
$product_cat = $product[0]['product_cat'];
$product_description = $product[0]['product_description'];
$product_date = $product[0]['product_date_added'];
$product_voorraad = $product[0]['voorraad'];
$product_price = $product[0]['product_price'];
$sale_price = $product[0]['sale_price'];
if(empty($sale_price)){
    $totaal = $product_price;
}else{
    $totaal = $sale_price;
}

$varProduct = array(
    "siteTitle" => $siteShort . " &bull; Webshop",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    ',
    "product" => $product,
    "product_name" => $product_name,
    "product_cat" => $product_cat,
    "product_description" => $product_description,
    "product_date" => $product_date,
    "product_voorraad" => $product_voorraad,
    "product_price" => $product_price,
    "totaal" => $totaal
);
// Defineer de pagina assigns.
$template->assign($varProduct);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'product');
$template->draw($siteTemplate . '_footer');
?>