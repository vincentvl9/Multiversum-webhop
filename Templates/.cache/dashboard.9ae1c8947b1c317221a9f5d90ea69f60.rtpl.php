<?php if(!class_exists('raintpl')){exit;}?><body>
<h1 class="header center orange-text">Dashboard</h1>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p>Hi <?php echo $username;?></p>
            <h3>Jouw gegevens</h3>
            <p>IP: <strong><?php echo $ip;?></strong></p>
            <p>Actief: <strong><?php echo $active;?></strong></p>
            <p>Je geheime login token: <strong><?php echo $token;?></strong></p>
        </div>
    </div>
</div>
<div class="col s5">