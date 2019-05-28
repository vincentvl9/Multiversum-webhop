<?php
require_once 'includes/framework.php';
// Inloggen
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}
if (isset($_GET['id'])) {
    Core::deleteProduct($_GET['id']);
    Core::forcePage('admin-products');
}

$varAdminProductDelete = array(
    "siteTitle" => $siteShort . " &bull; Verwijder product",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => ''
);
// Defineer de pagina assigns.
$template->assign($varAdminProductDelete);
