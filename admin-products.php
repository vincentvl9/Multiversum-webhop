<?php
require_once 'includes/framework.php';
// Inloggen
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}
$varAdminProducts = array(
    "siteTitle" => $siteShort . " &bull; Producten",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varAdminProducts);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-products');
$template->draw($siteTemplate . '_footer');
?>