<?php if(!class_exists('raintpl')){exit;}?><div class="container">
    <br><br>
    <h1 class="header center multiversumHeader">Multiversum</h1>
    <div class="row center">
        <h5 class="header col s12 light">Voeg een product toe aan de webshop.</h5>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col s12">
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="product_name" placeholder="Product naam" required>
                <select name="product_cat">
                    <?php echo Core::adminGetCatogories(); ?>
                </select>
                <p>Plaatjes</p>
                <input type="file" id="pictures" name="pictures[]" multiple >
                <input type="text" name="product_voorraad" placeholder="Voorraad" required>
                <input type="text" name="product_price" placeholder="Prijs" required>
                <label for="product_description">Product beschrijving</label>
                <textarea name="product_description" cols="30" rows="10" required></textarea>
                <button type="submit" name="submit" value="submit" class="btn yellowButton">Voeg toe!</button>
            </form>
        </div>
    </div>
</div>
<script> $(function() { $('textarea').froalaEditor() }); </script>