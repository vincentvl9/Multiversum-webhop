<?php
require_once 'Classes/classConfig.php';

//Database

Config::W('dbHost', 'localhost');
Config::W('dbUser', 'root');
Config::W('dbPass', '');
Config::W('dbName', 'webshop');

//Website

Config::W('siteLink', 'localhost');
Config::W('linkSources', 'templates/default/web-gallary/assets/');
Config::W('siteShort', 'Multiversum');
Config::W('siteTitle', 'Multiversum Webshop');
Config::W('siteVersion', '0.1');
Config::W('siteTemplate', 'default/');
Config::W('siteTemplateStyle', 'web-gallary/');


?>