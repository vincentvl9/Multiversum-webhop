<?php
require_once 'includes/framework.php';
// Inloggen

$varIndex = array(
    "siteTitle" => $siteShort . " &bull; Webshop",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varIndex);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'index');
$template->draw($siteTemplate . '_footer');
?>