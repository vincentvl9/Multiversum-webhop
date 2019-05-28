<?php
require_once 'includes/framework.php';
// Inloggen

$varContact = array(
    "siteTitle" => $siteShort . " &bull; Contact",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varContact);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'contact');
$template->draw($siteTemplate . '_footer');
?>