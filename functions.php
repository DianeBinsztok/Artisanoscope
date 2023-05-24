<?php
/**
**activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


// STYLES (marche pas)
/*
add_filter('elementor/editor/localize_settings', function ($config) {
	$config['default_schemes']['color']['items'] = [
		'Principal' => '#6ec1e4',
		'Secondaire' => '#54595f',
		'Texte' => '#7a7a7a',
		'Accentué' => '#61ce70',
        'Test' => '#61ce70'
	];

	return $config;
}, 99);
*/

function theme_enqueue_styles(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // TODO 

    //styles custom artisanoscope
    wp_enqueue_style('single-artisan-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-single-artisan-style.css');
    wp_enqueue_style('content-single-product-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-content-single-product-style.css');
    wp_enqueue_style('archive-products-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-archive-products-style.css');
    wp_enqueue_style('wc-product-backoffice-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-wc-backoffice-style.css');
    wp_enqueue_style('workshop-card-style', get_stylesheet_directory_uri() .'/assets/custom-css/artisanoscope-workshops-cards-style.css');
    
}

// MODULES DE FONCTIONS
require_once( get_stylesheet_directory() . '/functions/artisanoscope-single-artisan-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-control-product-visibility.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-archive-products-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-single-product-functions.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-product-filters.php' );
require_once( get_stylesheet_directory() . '/functions/artisanoscope-backoffice-register-product.php' );
require_once( get_stylesheet_directory() . '/assets/svg/svg.php' );
//Les emails:
require_once( get_stylesheet_directory() . '/functions/artisanoscope-order-email-functions.php' );


// TODO: CONFORMITÉ RGPD


// Scripts:
// PB: les scripts sont pris en compte mais s'exécutent trop tôt: l'élément de DOM n'existe pas encore et le script renvoie une erreur quand il ne le trouve pas => je tente de les appeler uniquement dans les templates où ils sont nécessaires
function artisanoscope_register_frontend_scripts(){
    wp_register_script("navbar_global", get_stylesheet_directory_uri().'/assets/js/artisanoscopeNavbarScript.js', true);
    wp_register_script("navbar_home", get_stylesheet_directory_uri().'/assets/js/artisanoscopeNavbarHomeScript.js', true);
    if(is_front_page()){
        wp_enqueue_script("navbar_home");
    }else{
        wp_enqueue_script("navbar_global");
    }

    //wp_enqueue_script("childrenMessage", get_stylesheet_directory_uri().'/assets/js/artisanoscopeChildrenTicketsMessage.js');
    //wp_enqueue_script("filtersDisplay", get_stylesheet_directory_uri().'/assets/js/artisanoscopeDisplayProductsFilters.js');
    //wp_enqueue_script("scheduleCheck", get_stylesheet_directory_uri().'/assets/js/artisanoscopeIncoherentHoursMessage.js');
    //wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri().'/assets/js/custom-scripts.js');
}
add_action( 'wp_enqueue_scripts', 'artisanoscope_register_frontend_scripts');

function artisanoscope_generate_date_options() {
    $date_options = [];
    $today = date('Y-m-d');
    $last_option_day = date('Y-m-d', strtotime('+ 12 months'));

    $period = new DatePeriod(
        new DateTime($today),
        new DateInterval('P1D'),
        new DateTime($last_option_day)
    );
    foreach ($period as $day => $value) {
        array_push($date_options, $value->format('d/m/Y'));
    }

    // Get the 'date' attribute taxonomy
    $taxonomy = 'pa_date'; // The 'pa_' prefix is for product attributes

    // Loop through each date and insert it as a term
    foreach ($date_options as $date_option) {
        if (!term_exists($date_option, $taxonomy)) {
            wp_insert_term($date_option, $taxonomy);
        }
    }
    // Supprimer les anciens termes de date
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);

    foreach ($terms as $term) {
        $term_date = DateTime::createFromFormat('d/m/Y', $term->name);
        if ($term_date < new DateTime()) {
            wp_delete_term($term->term_id, $taxonomy);
        }
    }
}
add_action('admin_init','artisanoscope_generate_date_options');

//En cours de réflexion: pour ajout d'un input de dates au dessus du champs d'options de produit
/*
function artisanoscope_register_backoffice_script() {
    $screen = get_current_screen();
    
    // Si je suis sur la page d'ajout de produit
    if ( ($screen->id == 'product' && $screen->action == 'add') || $screen->action == 'edit' ) {
        wp_register_script( "attributes-script", get_stylesheet_directory_uri().'/assets/js/artisanoscopeAttributeDatepicker.js', true );
        wp_enqueue_script( "attributes-script" );
    }
}
add_action( 'admin_enqueue_scripts', 'artisanoscope_register_backoffice_script' );
*/
/*
function artisanoscope_register_backoffice_script($hook) {
    $screen = get_current_screen();    
    // Si je suis sur la page d'ajout ou de modification de produit
    //if ( $screen->id == 'product') {
    //if($screen->action == 'add' || $screen->action == 'edit'){
    if( $hook == 'post.php' ||  $hook == 'post-new.php'){
        wp_register_script( "attributes-script", get_stylesheet_directory_uri().'/assets/js/artisanoscopeAttributeDatepicker.js', true );
        wp_enqueue_script( "attributes-script" );
    }
}
add_action( 'admin_enqueue_scripts', 'artisanoscope_register_backoffice_script' );
*/