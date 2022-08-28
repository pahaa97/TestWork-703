<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function theme_name_scripts() {
    wp_enqueue_style('custom', get_template_directory_uri() . '/custom.css');
}

    add_action( 'admin_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method( $hook ){
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
    wp_enqueue_script( 'newscript', get_template_directory_uri() . '/js/custom_script.js');
}

add_action( 'add_meta_boxes', 'test_add_metabox' );
function test_add_metabox() {
    add_meta_box(
        'testmeta', // ID нашего метабокса
        'Тестовые параметры товара', // заголовок
        'test_metabox_callback', // функция, которая будет выводить поля в мета боксе
        'product', // типы постов, для которых его подключим
        'normal', // расположение (normal, side, advanced)
        'high' // приоритет (default, low, high, core)
    );
}

function test_metabox_callback( $post ) {
    $image_product = get_post_meta( $post->ID, 'image_product', true );
    $default = get_site_url(). '/wp-content/uploads' . '/woocommerce-placeholder.png';
    if( $image_product && ( $image_attributes = wp_get_attachment_image_src( $image_product, array( 150, 110 ) ) ) ) {
        $src = $image_attributes[0];
    } else {
        $src = $default;
    }

    $date_product = get_post_meta( $post->ID, 'date_product', true );
    $type_array = ['rare', 'frequent', 'unusual'];
    $type_product = get_post_meta( $post->ID, 'type_product', true );

    echo '<table class="form-table">
		<tbody>
			<tr>
				<th><label for="image_product">Изображение</label></th>
				<td>
                    <img id="img_product" data-src="' . $default . '" src="' . $src . '" width="150" />
                    <div>
                        <input type="hidden" name="image_product" id="image_product" value="' . $image_product . '" />
                        <button type="submit" class="upload_image_button button">Загрузить</button>
                        <button type="submit" class="remove_image_button button">×</button>
                    </div>
                </td>
			</tr>
			<tr>
				<th><label>Дата создания</label></th>
				<td>
					<input type="date" name="date_product" value="' . esc_attr( $date_product ) . '" />
				</td>
			</tr>
			<tr>
				<th><label>Тип продукта</label></th>
				<td>';
    echo '<select name="type_product" id="type_product">';
    echo '<option value="-">-</option>';
    foreach ($type_array as $type) {
        echo '<option value="'.$type.'" '. (($type==$type_product) ? 'selected' : '') . '>'.$type.'</option>';
    }
    echo '</select>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="text-align: right;">
	<button type="submit" class="clear_fields button">Очистить поля</button>
	<button type="submit" class="update_fields button-primary">Обновить</button>
	</div>
	';
}

add_action( 'save_post', 'true_save_meta', 10, 2 );

function true_save_meta( $post_id, $post ) {
//    // проверка одноразовых полей
//    if ( ! isset( $_POST[ '_truenonce' ] ) || ! wp_verify_nonce( $_POST[ '_truenonce' ], 'seopostsettingsupdate-' . $post->ID ) ) {
//        return $post_id;
//    }
//
//    // проверяем, может ли текущий юзер редактировать пост
//    $post_type = get_post_type_object( $post->post_type );
//
//    if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
//        return $post_id;
//    }
//
//    // ничего не делаем для автосохранений
//    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
//        return $post_id;
//    }
//
//    // проверяем тип записи
//    if( 'product' !== $post->post_type ) {
//        return $post_id;
//    }

    if( isset( $_POST[ 'image_product' ] ) ) {
        update_post_meta( $post_id, 'image_product', $_POST[ 'image_product' ] );
    } else {
        delete_post_meta( $post_id, 'image_product' );
    }

    if( isset( $_POST[ 'date_product' ] ) ) {
        update_post_meta( $post_id, 'date_product', sanitize_text_field( $_POST[ 'date_product' ] ) );
    } else {
        delete_post_meta( $post_id, 'date_product' );
    }

    if( isset( $_POST[ 'type_product' ] ) ) {
        update_post_meta( $post_id, 'type_product', sanitize_text_field( $_POST[ 'type_product' ] ) );
    } else {
        delete_post_meta( $post_id, 'type_product' );
    }

    return $post_id;
}
