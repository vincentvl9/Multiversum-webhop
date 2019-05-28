<?php
require_once 'includes/framework.php';
if(Core::checkAdmin(Users::nameToId($_SESSION['user'])) === true){

}else{
    Core::forcePage('dashboard');
}

if(isset($_POST['submit'])){
    DB::insert('products', array(
        'product_name' => $_POST['product_name'],
        'product_cat' => $_POST['product_cat'],
        'voorraad' => $_POST['product_voorraad'],
        'product_price' => $_POST['product_price'],
        'product_description' => $_POST['product_description'],
        'product_date_added' => date("Y-m-d")
    ));
    $targetDir = "Templates/default/web-gallary/assets/images/products/";
    $allowTypes = array('jpg','png','jpeg','gif');

    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
    if(!empty(array_filter($_FILES['pictures']['name']))) {
        foreach ($_FILES['pictures']['name'] as $key => $val) {
            // File upload path
            $fileName = basename($_FILES['pictures']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            // Check whether file type is valid
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["pictures"]["tmp_name"][$key], $targetFilePath)) {
                    DB::insert('product_images', array(
                        "img_name" => $_FILES['pictures']['name'][$key],
                        "product_id" => Core::getLastProduct()
                    ));
                } else {
                    $errorUpload .= $_FILES['pictures']['name'][$key] . ', ';
                }
            } else {
                $errorUploadType .= $_FILES['pictures']['name'][$key] . ', ';
            }
        }
    }
    Core::forcePage('admin-products');
}


$varAdminProductsAdd = array(
    "siteTitle" => $siteShort . " &bull; Voeg product toe",
    "loginError" => "",
    "loadCss" => '
    ',
    "loadJs" => '
    '
);
// Defineer de pagina assigns.
$template->assign($varAdminProductsAdd);

$template->draw($siteTemplate . '_header');
$template->draw($siteTemplate . '_adminNavbar');
$template->draw($siteTemplate . 'admin-products-add');
$template->draw($siteTemplate . '_footer');
?>