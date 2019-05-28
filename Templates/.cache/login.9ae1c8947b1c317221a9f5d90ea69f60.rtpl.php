<?php if(!class_exists('raintpl')){exit;}?><body>
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
        <br><br>
        <h1 class="header center multiversumHeader">Multiversum</h1>
        <div class="row center">
            <h5 class="header col s12 light">Log in als beheerder</h5>
        </div>
        <br><br>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col s3"></div>
        <form class="col s6" method="POST">
            <div class="row">
                <h3>Login</h3>
                <div class="input-field col s12">
                    <input name="username" type="text" class="validate">
                    <label for="icon_prefix">Gebruikersnaam</label>
                </div>
                <div class="input-field col s12">
                    <input name="password"  type="password" class="validate">
                    <label for="icon_telephone">Wachtwoord</label>
                </div>
                <button class="btn btn-flat" type="login" name="login">Login</button>
            </div>
        </form>
</div>
</div>