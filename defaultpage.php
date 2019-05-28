<?php
require_once 'includes/framework.php';
// Inloggen

$varSupport = array(
    "siteTitle" => $siteShort . " &bull; Support",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varSupport);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'support');
$template->draw($siteTemplate . '_footer');
?>