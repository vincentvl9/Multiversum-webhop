<?php
require_once 'includes/framework.php';

if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}

$varAdminDashboard = array(
    "siteTitle" => $siteShort . " &bull; Admin Dashboard",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varAdminDashboard);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-dashboard');
$template->draw($siteTemplate . '_footer');
?>