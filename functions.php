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
function artisanoscope_check_product_schedule( $product ) {
    // Récupérer les horaires de début et de fin du produit
    $schedule_start = get_field( 'horaire_debut', $product->get_id() );
    $schedule_end = get_field( 'horaire_fin', $product->get_id() );

    // Si l'heure de fin précède l'heure de début, afficher un avertissement et empêcher l'enregistrement du produit
    if ( $schedule_start >= $schedule_end ) {
        // Afficher un message d'avertissement
        wc_add_notice( 'L\'horaire de fin doit être postérieur à l\'horaire de début.', 'error','woocommerce'  );

        // Empêcher l'enregistrement du produit
        $product->set_status( 'draft' );

        // Retourner le produit modifié
        return $product;
    }

    // Si l'intervalle d'horaire est cohérent, retourner le produit
    return $product;
}
add_filter( 'woocommerce_admin_process_product_object', 'artisanoscope_check_product_schedule', 10, 1 );

// TODO: ARCHIVE-PRODUCT: filtrage par catégorie

// TODO: ARCHIVE-PRODUCT: filtrage par artisan