<?php
require_once 'includes/framework.php';
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}

if(isset($_POST['cat_add'])){
    DB::insert('shop_catogories', array(
        'cat_name' => $_POST['cat_name']
    ));
    Core::forcePage('admin-catogories');
}
if(isset($_POST['cat_del'])){

    DB::delete('shop_catogories', "id=%s", $_POST['product_cat']);
}


$varAdminCatogories = array(
    "siteTitle" => $siteShort . " &bull; Categorieën beheer",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varAdminCatogories);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-catogories');
$template->draw($siteTemplate . '_footer');
?>