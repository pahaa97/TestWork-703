<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$post = (!empty($_POST)) ? true : false;

if($post) {
    $product_title = htmlspecialchars(trim($_POST['product_title']));
    $product_price = htmlspecialchars(trim($_POST['product_price']));
    $product_image = $_FILES['product_image'];
    $date_product = htmlspecialchars(trim($_POST['date_product']));
    $type_product = htmlspecialchars(trim($_POST['type_product']));
    $errors = [];
    if(!$product_title) {$errors[] = 'Укажите имя продукта ';}
    if(!$product_price) {$errors[] = 'Укажите цену продукта ';}
    if(!$product_image['name']) {$errors[] = 'Выберите изображение ';}
    if(!$date_product) {$errors[] = 'Укажите дату создания ';}
    if(!$type_product || $type_product === '-') {$errors[] = 'Выберите тип продукта ';}
    if(!$errors) {
        $image_id = media_handle_upload('product_image', 0);
        $product = createProductWoo($product_title, $product_price, $image_id);
        update_post_meta($product, 'image_product', $image_id);
        update_post_meta($product, 'date_product', $date_product);
        update_post_meta($product, 'type_product', $type_product);
        echo 'success';
    }
    else {
        echo '<div class="err">';
        foreach ($errors as $err) { echo '<li>'. $err .'</li>'; }
        echo '</div>';
    }
}

function createProductWoo($title, $price, $image) {
    $new_simple_product = new WC_Product_Simple();
    $new_simple_product->set_name($title);
    $new_simple_product->set_price($price);
    $new_simple_product->set_image_id($image);

    $new_simple_product->save();
    return $new_simple_product->get_id();
}
