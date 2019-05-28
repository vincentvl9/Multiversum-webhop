<?php
require_once 'includes/framework.php';
// Inloggen

$varBril = array(
    "siteTitle" => $siteShort . " &bull; bril",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varBril);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'wat_is_een_vr');
$template->draw($siteTemplate . '_footer');
?>