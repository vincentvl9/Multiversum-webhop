<?php
require_once 'includes/framework.php';
// Inloggen
if (isset($_POST['login'])) {
    $loginuser = htmlentities($_POST['username']);
    $loginpass = htmlentities(sha1($_POST['password']));

    if (empty($loginpass) || empty($loginuser)) {

        echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Vul iets in!</p></div>";

    } else {
        $user = DB::queryFirstRow("SELECT username,password FROM users WHERE username=%s AND password=%s", $loginuser, $loginpass);

        $count = DB::count();
        if ($count > 0) {
            $updateUser = DB::update('users', array(
                'token' => bin2hex(random_bytes(64)),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'active' => 1,
            ), "username=%s", $loginuser);
            $_SESSION['user'] = $loginuser;
            $_SESSION['time'] = time();
            $adminRole = Core::checkRole(Users::nameToId($_SESSION['user']));
            setcookie('role', $adminRole);
            Core::forcePage('admin-dashboard');
        } else {
            echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Je gebruikersnaam / wachtwoord is fout!</p></div>";
        }


    }
}
// registreren
if (isset($_POST['registreer'])) {
    if ($_POST['reg_password'] && $_POST['reg_password_check'] !== $_POST['reg_password']) {
        echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>De wachtwoorden zijn niet hezelfde!</p></div>";
    } else {
        $regusername = $_POST['reg_username'];
        $regpassword = $_POST['reg_password'];
        $regemail = $_POST['reg_email'];


        if (Core::userExists($regusername) === true) {
            echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Gebruikersnaam bestaat al!</p></div>";
        } elseif (Core::emailExists($regemail) === true) {
            echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Email bestaat al!</p></div>";
        }elseif(empty($_POST['registreer'])){
            echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Vul iets in!</p></div>";

        } else {
            DB::insert('users', array(
                'username' => $regusername,
                'password' => htmlentities(sha1($regpassword)),
                'email' => $regemail
            ));
            echo "<div class='z-depth-3 regSuccess'><p>Uw account is aangemaakt. U kunt nu<strong><a href='login'> inloggen.</a></strong></p></div>";
        }
    }
}

// Als ingelogd ga naar dashboard

if (isset($_SESSION['user'])) {
    Core::forcePage('admin-products');
}

$varLogin = array(
    "siteTitle" => $siteShort . " &bull; Login",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varLogin);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_navbar');
$template->draw($siteTemplate . 'login');
$template->draw($siteTemplate . '_footer');
?>