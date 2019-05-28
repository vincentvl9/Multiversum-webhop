<?php
require_once 'includes/framework.php';
// Check of gebruiker is ingelogd
if(!isset($_SESSION['user'])){
    Core::forcePage('index');
}

if(session_destroy())
{
    setcookie('role',0);
    $offline = DB::update('users', array(
      'active' => 0,
    ), "username=%s", $_SESSION['user']);
    Core::forcePage('index');
}

?>