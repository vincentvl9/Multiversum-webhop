<?php
require_once 'includes/framework.php';
// Check of gebruiker is ingelogd
if (!isset($_SESSION['user'])) {
    header('location: index.php');
}
//user info
$currentuser = Users::getCurrentUser(Users::nameToId($_SESSION['user']));

foreach($currentuser as $userinfo){

    $username = $userinfo['username'];
    $active = $userinfo['active'];
    $token = $userinfo['token'];
    $ip = $userinfo['ip'];
}
$varDashboard = array(
    "username" => $username,
    "token" => $token,
    "active" => $active,
    "ip" => $ip,
    "siteTitle" => $siteShort . " &bull; TestCMS",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);


// Defineer de pagina assigns.
$template->assign($varDashboard);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'dashboard');
$template->draw($siteTemplate . '_footer');
?>