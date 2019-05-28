<?php
require_once 'includes/framework.php';
// Inloggen
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}
$product = Core::adminEditProduct($_GET['id']);

$product_name = $product[0]['product_name'];
$product_cat = $product[0]['product_cat'];
$product_description = $product[0]['product_description'];
$product_date = $product[0]['product_date_added'];
$product_voorraad = $product[0]['voorraad'];
$product_price = $product[0]['product_price'];

if($sale_price = ''){
    $sale_price = NULL;
}else{
    $sale_price = $product[0]['sale_price'];
}

if(isset($_POST['submit'])) {
    DB::update('products', array(
        'product_name' => $_POST['product_name'],
        'product_cat' => $_POST['product_cat'],
        'product_description' => $_POST['product_description'],
        'voorraad' => $_POST['product_voorraad'],
        'sale_price' => $_POST['sale_price'],
        'product_price' => $_POST['product_price']
    ), "id=%s", $_GET['id']);
    Core::forcePage('admin-products-edit.php?id='. $_GET['id'] .'');

}


$varAdminProductsEdit = array(
    "siteTitle" => $siteShort . " &bull; Verander product informatie",
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
    "sale_price" => $sale_price,
    "product_price" => $product_price
);
// Defineer de pagina assigns.
$template->assign($varAdminProductsEdit);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-products-edit');
$template->draw($siteTemplate . '_footer');
?>