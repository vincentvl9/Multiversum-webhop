<?php
/**
* @name testCMS
* @copyright (c) 2018, Lex
* @author Lex  hello@lexmakesyour.site
* @version 0.1
**/


//Session starten
session_start();



// Nederlandse time zone
date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_ALL, "nl_NL");

// Defineer het systeem.
define("test", TRUE);

// Laad het core en users bestand in.
require_once 'Classes/classCore.php';
require_once 'Classes/classUsers.php';

// Laad de extensies in.
require_once 'Classes/extRainTPL.php';
require_once 'Classes/extMeekroDB.php';

//Configuratie
require_once 'config.php';

// Laad het template bestand in.
require_once 'Classes/classTemplate.php';
$template = new Template();

// Start met de basis variables.
$remoteIp = $_SERVER['REMOTE_ADDR'];
$time = time();
$siteShort = Config::R('siteShort');
$siteTitle = Config::R('siteTitle');
$siteVersion = Config::R('siteVersion');
$siteTemplate = Config::R('siteTemplate');
$siteTemplateStyle = Config::R('siteTemplateStyle');
$siteLink = Config::R('siteLink');
$siteDir = 'Templates/'.$siteTemplate.$siteTemplateStyle;


// Zet het belangrijkste om voor het front-end.
$varBasic = array(
    "siteName" => $siteTitle,
    "siteShort" => $siteShort,
    "siteTemplate" => 'Templates/'.$siteTemplate,
    "siteTemplateStyle" => 'Templates/'.$siteTemplate.'/'.$siteTemplateStyle,
); $template->assign($varBasic);


?>