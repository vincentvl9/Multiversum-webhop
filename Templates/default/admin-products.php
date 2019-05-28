<div class="container adminStats">
    <div class="row">
        <div class="col s12 m3 l3 adminStat">
            <h3>Producten</h3>
            <p>{function="Core::rowsProducts()"}</p>
        </div>
        <div class="col s12 m3 l3 adminStat">
            <h3>Categorieën</h3>
            <p>{function="Core::rowsCatogories()"}</p>
        </div>
        <div class="col s12 m3 l3 adminStat">
            <h3>Voorraad</h3>
            <p>{function="Core::totalStock()"} producten</p>
        </div>
        <div class="col s12 m3 l3 adminStat">
            <h3>Nieuw</h3>
            <p>{function="Core::addedToday()"}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col s6 m2 l2">
            <h5>Actie's</h5>
            <hr class="lineShop">
            <ul class="catogoriesList">
                <li class="shopCatogorie"><a href="admin-products-add">Product toevoegen</a></li>
                <li class="shopCatogorie"><a href="admin-catogories">Categorieën beheer </a></li>
            </ul>
        </div>
        <div class="products col s6 m10 l10 center">
            <h5>Producten</h5>
            <hr class="lineShop">
            <table class="highlight responsive-table">
                <thead>
                <tr>
                    <th>Product naam</th>
                    <th>Product voorraad</th>
                    <th>Toegevoegd</th>
                    <th>Prijs</th>

                </tr>
                </thead>
                <tbody>
                {function="Core::adminProducts()"}
                </tbody>
            </table>

        </div>
    </div>
</div>