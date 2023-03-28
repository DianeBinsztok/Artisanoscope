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
require_once( get_stylesheet_directory() . '/functions/artisanoscope-product-filters.php' );

// Réactiver la zone de widgets - pour le plugin filter-everything
/*
if (function_exists("register_sidebar")) {
  register_sidebar();
}
*/
add_action( 'widgets_init', 'artisanoscope_register_filter_widget_zone' );
function artisanoscope_register_filter_widget_zone() {

  $custom_sidebars = array(
    array(
      'name'          => 'Zone de filtrage des ateliers',
      'id'            => 'workshops-filter-area',
      'description'   => "La zone contiendra les widgets de filtres d'ateliers",
    )
  );

  $defaults = array(
    'name'          => 'Zone de widgets',
    'id'            => 'widgets-zone',
    'description'   => 'Zone de widgets',
    'class'         => '',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget'  => '</li>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>' 
  );

  foreach( $custom_sidebars as $sidebar ) {
    $args = wp_parse_args( $sidebar, $defaults );
    register_sidebar( $args );
  }

}
// TODO: CONFORMITÉ RGPD

// TODO: ARCHIVE-PRODUCT: filtrage par artisan

// TODO: ARCHIVE-PRODUCT: filtrage par intervalle de dates?

// TODO: FIX - DASHBOARD WOOCOMMERCE Vérifie si l'intervalle d'horaires est cohérent avant de sauvegarder le produit. PB: les scripts frontend ne se chargent pas dans le dashboard
function artisanoscope_check_product_schedule( $productID ) {
    // Récupérer les horaires de début et de fin du produit
    $startHour = get_field( 'horaire_debut', $productID);
    $endHour = get_field( 'horaire_fin', $productID);
    $product = wc_get_product($productID);

    // Si l'heure de fin précède l'heure de début, empêcher l'enregistrement du produit et afficher une notification
    if ( $startHour >= $endHour ) {
        
        if($product){
            $product->set_status( 'draft' );
        }
    }
}
//add_action( 'woocommerce_process_product_meta', 'artisanoscope_check_product_schedule', 10, 1 );

// PB: les scripts sont pris en compte mais s'exécutent trop tôt: l'élément de DOM n'existe pas encore et le script renvoie une erreur quand il ne le trouve pas => je tente de les appeler uniquement dans les templates où ils sont nécessaires
function artisanoscope_register_frontend_scripts(){
    //wp_enqueue_script("childrenMessage", get_stylesheet_directory_uri().'/assets/js/artisanoscopeChildrenTicketsMessage.js');
    //wp_enqueue_script("filtersDisplay", get_stylesheet_directory_uri().'/assets/js/artisanoscopeDisplayProductsFilters.js');
    //wp_enqueue_script("scheduleCheck", get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js');

    //wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri().'/assets/js/custom-scripts.js');
}
//add_action( 'wp_enqueue_scripts', 'artisanoscope_register_frontend_scripts');

function artisanoscope_register_admin_frontend_scripts(){
    echo('<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js" defer></script>');
    //wp_enqueue_script("scheduleCheck", get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js', true);
}
//add_action( 'admin_enqueue_scripts', 'artisanoscope_register_admin_frontend_scripts', true);