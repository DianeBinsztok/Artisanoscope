<?php
/**
**activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// STYLES
function theme_enqueue_styles(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    //styles custom artisanoscope
    wp_enqueue_style('single-artisan-style', get_stylesheet_directory_uri() .'/custom-css/single-artisan-style.css');
    wp_enqueue_style('content-single-product-style', get_stylesheet_directory_uri() .'/woocommerce/custom-css/content-single-product-style.css');
}

// MODULES DE FONCTIONS
require_once( get_stylesheet_directory() . '/functions/single-artisan-functions.php' );
require_once( get_stylesheet_directory() . '/functions/archive-product-functions.php' );
require_once( get_stylesheet_directory() . '/functions/content-single-product-functions.php' );


// TODO: CONFORMITÉ RGPD


// TODO: FIX - DASHBOARD WOOCOMMERCE Vérifie si l'intervalle d'horaire est cohérent avant de sauvegarder le produit
function artisanoscope_check_product_schedule( $productID ) {
    // Récupérer les horaires de début et de fin du produit
    $startHour = get_field( 'horaire_debut', $productID);
    $endHour = get_field( 'horaire_fin', $productID);
    $product = wc_get_product($productID);

    // Si l'heure de fin précède l'heure de début, empêcher l'enregistrement du produit et afficher une notification
    if ( $startHour >= $endHour ) {
        echo("TU VAS MARCHER BORDEL??????");
        if($product){
            $product->set_status( 'draft' );
        }
    }
}
add_action( 'woocommerce_process_product_meta', 'artisanoscope_check_product_schedule', 10, 1 );


// TODO: ARCHIVE-PRODUCT: filtrage par catégorie


// TODO: ARCHIVE-PRODUCT: filtrage par artisan