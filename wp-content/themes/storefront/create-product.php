<?php
/**
 * Template Name: Create Product
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <form id="create_product" class="create_product">
                <h3>Создание продукта</h3>
                <div id="note"></div>
                <div class="form-field">
<!--                    <label for="product_title">Название продукта</label>-->
                    <input type="text" name="product_title" placeholder="Название продукта">
                </div>

                <div class="form-field">
<!--                    <label for="product_price">Цена</label>-->
                    <input type="text" name="product_price" placeholder="Цена">
                </div>

                <div class="form-field">
<!--                    <label for="image_product">Изображение</label>-->
                    <input type="file" name="product_image" id="product_image">
                </div>

                <div class="form-field">
<!--                    <label>Дата создания</label>-->
                    <input type="date" name="date_product">
                </div>

                <div class="form-field">
<!--                    <label>Тип продукта</label>-->
                    <select name="type_product" id="type_product">
                            <option value="-" disabled>Выберите тип продукта</option>
                            <option value="rare">rare</option>
                            <option value="frequent">frequent</option>
                            <option value="unusual">unusual</option>
                    </select>
                </div>
                <input type="submit" class="button button-primary" value="Создать">
            </form>

		</main><!-- #main -->
	</div><!-- #primary -->
    <script src="/wp-content/themes/storefront/js/create_product.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
do_action( 'storefront_sidebar' );
get_footer();
