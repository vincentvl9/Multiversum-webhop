<?php
require_once 'includes/framework.php';
// Inloggen
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}
$product = Core::adminGetProduct($_GET['id']);



$varAdminProductsRead = array(
    "siteTitle" => $siteShort . " &bull; Informatie over product",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '',
    "product" => $product
);
// Defineer de pagina assigns.
$template->assign($varAdminProductsRead);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-products-read');
$template->draw($siteTemplate . '_footer');
?>