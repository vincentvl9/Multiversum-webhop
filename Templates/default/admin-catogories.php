<div class="container">
    <br><br>
    <h1 class="header center multiversumHeader">Multiversum</h1>
    <div class="row center">
        <h5 class="header col s12 light">Voeg een catogorie toe aan de webshop.</h5>
    </div>
</div>
<div class="container">
    <div class="row">
        <h4>Catogorie toevoegen:</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="cat_name" placeholder="Catogorie" required>
            <button type="submit" name="cat_add" value="cat_add" class="btn yellowButton">Voeg toe</button>
        </form>
    </div>
    <div class="row">
        <h4>Catogorie verwijderen</h4>
        <form method="post">
            <select name="product_cat">
                {function="Core::adminGetCatogories()"}
            </select>
            <button  onclick="return confirm('Weet je zeker dat je deze catogorie wilt verwijderen?');"class="btn yellowButton" type="submit" name="cat_del" value="cat_del">Verwijder</button>
        </form>
    </div>
</div>
<script> $(function () {
        $('textarea').froalaEditor()
    }); </script>