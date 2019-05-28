<div class="container">
    <br><br>
    <h1 class="header center multiversumHeader">Multiversum</h1>
    <div class="row center">
        <h5 class="header col s12 light">Alle producten in u winkelwagen.</h5>
    </div>
    <div class="row">
        <div class="winkelwagen">
            <table>
                <thead>
                    <th>Product naam</th>
                    <th>Aantal</th>
                    <th>Product price</th>
                    <th>Verwijder</th>
                </thead>
                <tbody>
                    {function="Core::getCartItems()"}
                    {function="Core::cartTotal()"}
                </tbody>
            </table>
        </div>
    </div>
</div>