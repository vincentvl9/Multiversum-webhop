<div class="container">
    <br><br>
    <h1 class="header center multiversumHeader">Multiversum</h1>
    <div class="row center">
        <h5 class="header col s12 light">Verander product informatie.</h5>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col s12">
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="product_name" value="{$product_name}" required>
                <select name="product_cat">
                    {function="Core::adminGetCatogories()"}
                </select>
                <input type="text" name="product_voorraad" value="{$product_voorraad}" required>
                <input type="text" name="product_price" value="{$product_price}" required>
                <p>Aanbieding toevoegen: Typ zowel product prijs als aanbieding prijs. Weghalen: verander aanbieding prijs naar 0.</p>
                <input type="text" name="sale_price" placeholder="Aanbieding prijs! Als n.v.t laat het leeg" value="{$sale_price}">
                <label for="product_description">Product beschrijving</label>
                <textarea name="product_description"  cols="30" rows="10" required>{$product_description}</textarea>
                <button type="submit" name="submit" value="submit" class="btn yellowButton">Verander</button>
                <a class="btn yellowButton" href="admin-products.php">Terug</a>
            </form>
        </div>
    </div>
</div>
<script> $(function() { $('textarea').froalaEditor() }); </script>