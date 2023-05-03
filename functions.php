<?php
/**
**activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// STYLES
function theme_enqueue_styles(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // TODO 

    //styles custom artisanoscope
    wp_enqueue_style('single-artisan-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-single-artisan-style.css');
    wp_enqueue_style('content-single-product-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-content-single-product-style.css');
    wp_enqueue_style('navbar-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-navbar-style.css');
    wp_enqueue_style('wc-product-backoffice-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-wc-backoffice-style.css');
    
}

// MODULES DE FONCTIONS
require_once( get_stylesheet_directory() . '/functions/artisanoscope-single-artisan-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-archive-products-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-single-product-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-product-filters.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-backoffice-register-product.php' );


// TODO: CONFORMITÉ RGPD



// TODO: FIX - DASHBOARD WOOCOMMERCE Vérifie si l'intervalle d'horaires est cohérent avant de sauvegarder le produit.
function artisanoscope_check_product_schedule( $productID ) {
    // Récupérer les horaires de début et de fin du produit
    $startHour = get_field( 'prod_heure_debut', $productID);
    $endHour = get_field( 'prod_heure_debut', $productID);
    $product = wc_get_product($productID);

    // Si l'heure de fin précède l'heure de début, empêcher l'enregistrement du produit et afficher une notification
    if ( strtotime($startHour) >= strtotime($endHour) ) {
        
        if($product){
            $product->set_status( 'draft' );
        }
    }
}
add_action( 'woocommerce_process_product_meta', 'artisanoscope_check_product_schedule', 10, 1 );

// PB: les scripts sont pris en compte mais s'exécutent trop tôt: l'élément de DOM n'existe pas encore et le script renvoie une erreur quand il ne le trouve pas => je tente de les appeler uniquement dans les templates où ils sont nécessaires
function artisanoscope_register_frontend_scripts(){
    //wp_enqueue_script("navbar", get_stylesheet_directory_uri().'/assets/js/artisanoscopeNavbarScript.js', true);
    //wp_enqueue_script("childrenMessage", get_stylesheet_directory_uri().'/assets/js/artisanoscopeChildrenTicketsMessage.js');
    //wp_enqueue_script("filtersDisplay", get_stylesheet_directory_uri().'/assets/js/artisanoscopeDisplayProductsFilters.js');
    //wp_enqueue_script("scheduleCheck", get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js');

    //wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri().'/assets/js/custom-scripts.js');
}
//add_action( 'wp_enqueue_scripts', 'artisanoscope_register_frontend_scripts');

// Scripts:
//add_action( 'activate_wp_head', 'artisanoscope_register_scripts' );
function artisanoscope_register_scripts()
{
    wp_enqueue_script("navbar", get_stylesheet_directory_uri().'/assets/js/artisanoscopeNavbarScript.js', true);
}

function artisanoscope_register_admin_frontend_scripts(){
    //echo('<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js" defer></script>');
    //wp_enqueue_script("scheduleCheck", get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js', true);
}
//add_action( 'admin_enqueue_scripts', 'artisanoscope_register_admin_frontend_scripts', true);
//add_action('admin_init','artisanoscope_register_admin_frontend_scripts');