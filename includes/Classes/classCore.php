<?php

/**
 * De Core class wordt gebruikt voor alles wat met het systeem of de database te maken heeft.
 * @example Core::forcePage('page') Stuur door naar de betreffende pagina.
 */
class Core
{


    /**
     * Stuur door naar de opgegeven pagina.
     * @param string $page Welke pagina wil je opvragen?
     */
    public static function forcePage($page = '')
    {
        header('Location: ' . $page);
    }

    /**
     * Checkt of de gebruiker in de database bestaat doormiddel van de naam
     * @param string $name is de naam die je wilt checken
     * @return true/false
     */
    public static function userExists($name)
    {
        DB::query("SELECT * FROM users WHERE username=%s", $name);
        $username = DB::count();


        if ($username > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checkt of de gebruiker in de database bestaat doormiddel van de email
     * @param string $email is de email die je wilt checken
     * @return true/false
     */
    public static function emailExists($email)
    {
        DB::query("SELECT * FROM users WHERE email=%s", $email);
        $email = DB::count();

        if ($email > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getProductPic($id)
    {
        $pictureData = DB::query("Select products.id, product_images.* FROM products INNER JOIN product_images ON products.id = product_images.product_id WHERE products.id=%s LIMIT 1", $id);
        if (empty($pictureData)) {
            $picture = "default.png";
        } else {
            $picture = $pictureData[0]['img_name'];
        }
        return "<img class='responsive-img materialboxed' src='./Templates/default/web-gallary/assets/images/products/" . $picture . "'>";
    }

    public static function orderByCato()
    {
        $catogorie = $_GET['catogorie'];
        $products = DB::query("SELECT shop_catogories.*, products.* FROM shop_catogories INNER JOIN products ON shop_catogories.id = products.product_cat WHERE shop_catogories.cat_name=%s", $catogorie);

        foreach ($products as $product) {
            echo "<div class='product'>";
            echo "<div class='productPictureShop'>";
            echo self::getProductPic($product['id']);
            echo "</div>";
            echo "<div class='productTitle'>";
            echo "<h6>" . $product['product_name'] . "</h6>";
            echo "</div>";
            echo "<div class='productPrice'>";
            if (empty($product['sale_price'])) {
                echo "<p> &euro;  " . $product['product_price'] . "</p>";
            } else {
                echo "<p class='saleOrgPrice'> &euro;  " . $product['product_price'] . "</p>";
                echo "<p class='salePrice'> &euro;  " . $product['sale_price'] . "</p>";
            }
            echo "</div>";
            echo "<a class='waves-effect waves-light btn yellowButton' href='product.php?id=" . $product['id'] . "'>Meer informatie</a>";
            echo "<a class='waves-effect waves-light btn yellowButton shopAddCart' href='shopping-cart-add.php?id=" . $product['id'] . "'><i class=\"fas fa-cart-plus\"></i></a>";
            echo "</div>";
        }
    }

    public static function searchProduct()
    {
        $input = $_GET['searchProduct'];
        if (empty($input)) {

        } else {
            $products = DB::query("SELECT * FROM products WHERE product_name LIKE %ss", $input);

            foreach ($products as $product) {
                echo "<div class='product'>";
                echo "<div class='productPictureShop'>";
                echo self::getProductPic($product['id']);
                echo "</div>";
                echo "<div class='productTitle'>";
                echo "<h6>" . $product['product_name'] . "</h6>";
                echo "</div>";
                echo "<div class='productPrice'>";
                if (empty($product['sale_price'])) {
                    echo "<p> &euro;  " . $product['product_price'] . "</p>";
                } else {
                    echo "<p class='saleOrgPrice'> &euro;  " . $product['product_price'] . "</p>";
                    echo "<p class='salePrice'> &euro;  " . $product['sale_price'] . "</p>";
                }
                echo "</div>";
                echo "<a class='waves-effect waves-light btn yellowButton' href='product.php?id=" . $product['id'] . "'>Meer informatie</a>";
                echo "<a class='waves-effect waves-light btn yellowButton shopAddCart' href='shopping-cart-add.php?id=" . $product['id'] . "'><i class=\"fas fa-cart-plus\"></i></a>";

                echo "</div>";
            }
        }
    }

    public static function getAllProducts()
    {
        if (isset($_GET['searchProduct'])) {
            self::searchProduct();
        } elseif (isset($_GET['catogorie'])) {
            self::orderByCato();
        } elseif (isset($_GET['sale'])) {
            self::showSale();
        } elseif (!isset($_GET['searchProduct'], $_GET['catogorie'])) {
            $products = DB::query("SELECT * FROM products");

            foreach ($products as $product) {
                echo "<div class='product'>";
                echo "<div class='productPictureShop'>";
                echo self::getProductPic($product['id']);
                echo "</div>";
                echo "<div class='productTitle'>";
                echo "<h6>" . $product['product_name'] . "</h6>";
                echo "</div>";
                echo "<div class='productPrice'>";
                if (empty($product['sale_price'])) {
                    echo "<p> &euro;  " . $product['product_price'] . "</p>";
                } else {
                    echo "<p class='saleOrgPrice'> &euro;  " . $product['product_price'] . "</p>";
                    echo "<p class='salePrice'> &euro;  " . $product['sale_price'] . "</p>";
                }
                echo "</div>";
                echo "<a class='waves-effect waves-light btn yellowButton' href='product.php?id=" . $product['id'] . "'>Meer informatie</a>";
                echo "<a class='waves-effect waves-light btn yellowButton shopAddCart' href='shopping-cart-add.php?id=" . $product['id'] . "'><i class=\"fas fa-cart-plus\"></i></a>";
                echo "</div>";
            }
        }
    }

    public static function getAllCatogories()
    {
        $catogories = DB::query("SELECT * FROM shop_catogories");

        foreach ($catogories as $catogorie) {
            echo "<li class='shopCatogorie'><a href='shop?catogorie=" . $catogorie['cat_name'] . "'>" . $catogorie['cat_name'] . "</a></li>";
        }
    }

    public static function getSingleProduct()
    {
        $product = DB::query("SELECT * FROM products WHERE id =%s", $_GET['id']);
        $error = "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Product niet gevonden!<a href='shop'> Klik hier om alle producten te weergeven</a></p></div>";
        if (empty($product)) {
            error_reporting(0);
            echo $error;
        } else {
            return $product;
        }
    }

    public static function checkAdmin($id)
    {
        $checkAdmin = DB::query("SELECT role FROM users WHERE id =%s", $id);
        $admin = $checkAdmin[0]['role'];
        if ($admin > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkRole($id)
    {
        $checkRole = DB::query("SELECT role FROM users WHERE id =%s", $id);
        $adminRole = $checkRole[0]['role'];

        return $adminRole;
    }

    public static function adminProducts()
    {
        $products = DB::query("SELECT * FROM products");
        if (empty($products)) {
            echo "<div class='z-depth-3 loginError'><p><strong>Error: </strong>Nog geen producten aanwezig!<a href='shop'></a></p></div>";
            error_reporting(0);
        } else {
            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>" . $product['product_name'] . "</td>";
                echo "<td>" . $product['voorraad'] . "</td>";
                echo "<td>" . $product['product_date_added'] . "</td>";
                echo "<td>" . $product['product_price'] . "</td>";
                echo "<td><a href='admin-products-read.php?id=" . $product['id'] . "'>Lezen</a></td>";
                echo "<td><a href='admin-products-edit.php?id=" . $product['id'] . "'>Veranderen</a></td>";
                echo "<td><a href='admin-products-delete.php?id=" . $product['id'] . "'  onclick=\"return confirm('Weet je zeker dat je dit productt wilt verwijderen?');\">Verwijderen</a></td>";
                echo "</tr>";
            }
        }
    }

    public static function adminGetCatogories()
    {
        $catogories = DB::query("SELECT * FROM shop_catogories");

        foreach ($catogories as $catogorie) {
            echo "<option value='" . $catogorie['id'] . "'>" . $catogorie['cat_name'] . "</option>";
        }
    }

    public static function adminGetProduct($id)
    {
        $product = DB::query('SELECT * FROM products WHERE id=%s', $id);

        return $product;

    }

    public static function getLastProduct()
    {
        $lastproduct = DB::query("SELECT id FROM products  ORDER BY ID DESC LIMIT 1");

        return $lastproduct['0']['id'];
    }

    public static function getProductPics()
    {
        $picturesData = DB::query("Select products.id, product_images.* FROM products INNER JOIN product_images ON products.id = product_images.product_id WHERE products.id=%s", $_GET['id']);

        foreach ($picturesData as $pictureData) {
            echo "<li><img class='' src='./Templates/default/web-gallary/assets/images/products/" . $pictureData['img_name'] . "'></li>";
        }
    }

    public static function deleteProduct($id)
    {
        DB::delete('products', "id=%s", $id);
    }

    public static function adminEditProduct($id)
    {
        $product = DB::query("SELECT * FROM products WHERE id=%s", $id);

        return $product;
    }

    public static function rowsProducts()
    {
        DB::query("SELECT * FROM products");
        $counter = DB::count();
        echo $counter;
    }

    public static function rowsCatogories()
    {
        DB::query("SELECT * from shop_catogories");
        $counter = DB::count();
        echo $counter;
    }

    public static function totalStock()
    {
        $products = DB::query("SELECT voorraad FROM products");
        $voorraad = 0;
        foreach ($products as $product) {
            $voorraad = $product['voorraad'] + $voorraad;
        }
        echo $voorraad;
    }

    public static function addedToday()
    {
        $products = DB::query("SELECT product_date_added FROM products");
        $addedToday = 0;
        foreach ($products as $product) {
            if ($product['product_date_added'] == date("Y-m-d")) {
                $addedToday += 1;
            } else {
                $addedToday = 0;
            }
            echo $addedToday;
        }
    }

    public static function showSale()
    {
        $products = DB::query("SELECT * FROM products WHERE sale_price>1.01");

        foreach ($products as $product) {
            echo "<div class='product'>";
            echo "<div class='productPictureShop'>";
            echo self::getProductPic($product['id']);
            echo "</div>";
            echo "<div class='productTitle'>";
            echo "<h6>" . $product['product_name'] . "</h6>";
            echo "</div>";
            echo "<div class='productPrice'>";
            if (empty($product['sale_price'])) {
                echo "<p> &euro;  " . $product['product_price'] . "</p>";
            } else {
                echo "<p class='saleOrgPrice'> &euro;  " . $product['product_price'] . "</p>";
                echo "<p class='salePrice'> &euro;  " . $product['sale_price'] . "</p>";
            }
            echo "</div>";
            echo "<a class='waves-effect waves-light btn yellowButton' href='product.php?id=" . $product['id'] . "'>Meer informatie</a>";
            echo "<a class='waves-effect waves-light btn yellowButton shopAddCart' href='shopping-cart-add.php?id=" . $product['id'] . "'><i class=\"fas fa-cart-plus\"></i></a>";
            echo "</div>";
        }
    }

    public static function addToCart($id)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        if(in_array($_GET['id'], $_SESSION['cart'])){
            self::forcePage('shop');

        }else{
            $cart = $id;
            $random = rand( 10, 10);
            $cartArray = array(
              "product_id" => $cart,
              "random" => $random
            );
            array_push($_SESSION['cart'], $cart);
            self::forcePage('shop');
        }
    }

    public static function cartItems()
    {
        if (isset($_SESSION['cart'])) {
            $cartItems = count($_SESSION['cart']);
        } else {
            $cartItems = 0;
        }
        return $cartItems;

    }

    public static function getCartItems()
    {

        foreach ($_SESSION['cart'] as $item) {
                        $product = DB::query("SELECT id, product_name, product_price FROM products WHERE id=%s", $item);
            echo "<tr>";
            echo "<td>" . $product[0]['product_name'] . "</td>";
            echo "<td> 1 </td>";
            echo "<td>" . $product[0]['product_price'] . "</td>";
            echo "<td><a class='shoppingCartRemove' href='shopping-cart-delete?id=" . $item . "'><i class=\"fas fa-minus-square\"></i></a></td>";
            echo "</tr>";

        }
    }

    public static function cartTotal()
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $product = DB::query("SELECT product_price FROM products WHERE id=%s", $item);
            $total = $total + $product[0]['product_price'];
        }
        $totalex = round($total / 100 * 79, 2);
        echo "<tr>";
        echo "<td class='shopTotal'><strong>Totaal INCL:</strong> &euro;". $total ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td class='shopTotal'><strong>Totaal EXCL:</strong> &euro;". $totalex ."</td>";
        echo "</tr>";
        var_dump($_SESSION['cart']);
    }
    public static function removeCart(){
        unset($_SESSION['cart'][0]);
        self::forcePage('shoppingcart');
    }
}
