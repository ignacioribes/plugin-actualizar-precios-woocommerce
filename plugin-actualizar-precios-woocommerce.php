<?php
/*
Plugin Name: Actualización de precios mensual
Description: Actualiza los precios de los productos cada mes automáticamente.
Version: 1.0
Author: Tu nombre
Author URI: Tu URL
*/

// Crea una nueva tarea cron para actualizar los precios cada mes
function update_product_prices() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            global $product;

            $value_multiplier = 1.05;

            $regular_price = $product->get_regular_price();
            $update_regular_price = $regular_price * $value_multiplier;
            update_post_meta($product->get_id(), '_regular_price', $update_regular_price);

            $sale_price = $product->get_sale_price();
            $update_sale_price = $sale_price * $value_multiplier;
            update_post_meta($product->get_id(), '_price', $update_sale_price);

        endwhile;
    }
    wp_reset_query();
}

// Agrega la tarea cron en WordPress
add_action('update_product_prices_cron', 'update_product_prices');

// Programa la tarea cron para que se ejecute cada mes
function schedule_product_price_update() {
    if ( ! wp_next_scheduled( 'update_product_prices_cron' ) ) {
        wp_schedule_event( time(), 'monthly', 'update_product_prices_cron' );
    }
}
add_action( 'wp', 'schedule_product_price_update' );
?>
