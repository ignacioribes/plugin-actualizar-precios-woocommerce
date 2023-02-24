<?php
/*
Plugin Name: Actualización de precios mensual
Description: Actualiza los precios de los productos cada mes automáticamente.
Version: 1.0
Author: Ignacio Ribes
Author URI: https://github.com/ignacioribes/
*/

// Crea una nueva tarea cron para actualizar los precios cada mes
function update_product_prices() {
    $value_multiplier = get_option( 'update_product_prices_multiplier', 1.05 );
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            global $product;

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

// Agrega la opción de configuración para actualizar el valor multiplicador
function update_product_prices_settings() {
    add_settings_section(
        'update_product_prices_settings_section',
        'Actualizar precio de producto cada mes',
        'update_product_prices_settings_section_callback',
        'general'
    );

    add_settings_field(
        'update_product_prices_multiplier',
        'Valor multiplicador para actualizar precios',
        'update_product_prices_multiplier_callback',
        'general',
        'update_product_prices_settings_section'
    );

    register_setting( 'general', 'update_product_prices_multiplier' );
}
add_action( 'admin_menu', 'update_product_prices_settings' );

// Renderiza el texto de la sección de configuración
function update_product_prices_settings_section_callback() {
    echo 'Configura el valor multiplicador que se usará para actualizar los precios de los productos cada mes.';
}

// Renderiza el campo de entrada de la opción de configuración
function update_product_prices_multiplier_callback() {
    $value_multiplier = get_option( 'update_product_prices_multiplier', 1.05 );
    echo '<input type="number" step="0.01" min="0" id="update_product_prices_multiplier" name="update_product_prices_multiplier" value="' . $value_multiplier . '" />';
}
?>
