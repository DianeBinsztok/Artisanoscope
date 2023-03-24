<?php
// Afficher les filtres
function artisanoscope_display_workshops_custom_filters() {
    // 2 - Les artisans
    // a - Si c'est un champs ACF (utiliser $artisan dans la boucle)
    $artisans=[];
    $args = array('post_type' => 'artisan', 'post_status' => 'publish','posts_per_page' => -1);
    $artisansRaw = get_posts($args);
    if ( $artisansRaw) {
        foreach($artisansRaw as $artisan){
            array_push($artisans, $artisan);
        }
    }
    
    // 3 - Les catégories d'artisanat
    $crafts=get_terms('product_cat', array('hide_empty' => true));
    echo('
        <div id="artisanoscope-custom-filters-container">
            <form id="artisanoscope-custom-filters-form" method="get" style="display:flex; justify-content:space-around;">
                <div id="artisanoscope-artisan-filter-container">
                    <select name="art" id="artisanoscope-artisan-filter" style="width:100%">
                    <option value="all">Tous les artisans</option>');

                    foreach ($artisans as $artisan){
                        if (isset($_GET['art']) && $_GET['art'] === $artisan->post_name){
                            echo('<option value="'.$artisan->post_name.'" selected>'.$artisan->post_title.'</option>');
                        }else{
                            echo('<option value="'.$artisan->post_name.'">'.$artisan->post_title.'</option>');
                        }
                        
                    }

                    echo('
                    </select>
                </div>
                <div id="artisanoscope-craft-filter-container">
                    <select name="cat" id="artisanoscope-craft-filter">
                        <option value="all">Toutes les catégories</option>');
                        foreach ($crafts as $craft){
                            if (isset($_GET['cat']) && $_GET['cat'] === $craft->slug){
                                echo('<option value="'.$craft->slug.'" selected>'.$craft->name.'</option>');
                            }else{
                                echo('<option value="'.$craft->slug.'">'.$craft->name.'</option>');
                            }
                            
                        }
                    echo('</select>
                </div>
            </form>
        </div>');  
        wp_enqueue_script("customFilters", get_stylesheet_directory_uri().'/assets/js/artisanoscopeWorkshopsCustomFilters.js');
}
add_action( 'woocommerce_before_shop_loop', 'artisanoscope_display_workshops_custom_filters', 20 );

function artisanoscope_workshops_custom_filters($visible, $productId){
    // L'artisan
	$artisan = get_field("artisan");
    $artisan_slug = $artisan->post_name;

    // Les catégories
    $categories = get_the_terms( $productId, 'product_cat' );
    $category_slugs = [];
    foreach($categories as $category){
        array_push($category_slugs, $category->slug);
    }
    
    if(isset($_GET['art'])||isset($_GET['cat'])){
        if(isset($_GET['art'])){
            $artisan_query = $_GET['art'];
            if($artisan_slug === $artisan_query){
                return $visible;
            }
        }
        if(isset($_GET['cat'])){
            $category_query = $_GET['cat'];
            if(in_array($category_query, $category_slugs)){
                return $visible;
            }
        }
    }else{
        return $visible;
    }
}
add_filter('woocommerce_product_is_visible', 'artisanoscope_workshops_custom_filters', 10, 2);