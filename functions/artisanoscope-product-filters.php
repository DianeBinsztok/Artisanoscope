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
    
    // b - Si c'est une custom taxonomie (utiliser $artisan->name)
    //$artisans = get_terms('artisan', array('hide_empty' => false));

    // 3 - Les catégories d'artisanat
    $crafts=get_terms('product_cat', array('hide_empty' => true));
    echo('
        <div id="artisanoscope-custom-filters-container">
            <form id="artisanoscope-custom-filters-form" method="get" style="display:flex; justify-content:space-around;">');

    echo('
                <div id="artisanoscope-artisan-filter-container">
                    <select name="art" id="artisanoscope-artisan-filter" style="width:100%">
                    <option value="all">Artisans</option>');

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
                        <option value="all">Types d\'artisanat</option>');
                        foreach ($crafts as $craft){
                            if (isset($_GET['cat']) && $_GET['cat'] === $craft->slug){
                                echo('<option value="'.$craft->slug.'" selected>'.$craft->name.'</option>');
                            }else{
                                echo('<option value="'.$craft->slug.'">'.$craft->name.'</option>');
                            }
                        }
                    echo('</select>
                </div>');  
        
        // La période de dates:
        if (isset($_GET['du'])){
            $today = $_GET['du'];
        }else{
            $today = date("Y-m-d");
        }
        if (isset($_GET['au'])){
            $oneMonthOn = $_GET['au'];
        }else{
            $oneMonthOn = date("Y-m-d", strtotime(date("Y-m-d", strtotime($today)). " + 30 day"));
        }
            echo('
                <div id="artisanoscope-daterange-filter">
                    <button id="artisanoscope-daterange-filter-button" type="button">Dates</button>
                    <div id="artisanoscope-daterange-toggle-zone" class="hide" style="display:flex;">
                        <input type="date" id="artisanoscope-daterange-start" name="du" value="'.$today.'" />
                        <input type="date" id="artisanoscope-daterange-end"  name="au" value="'.$oneMonthOn.'" />
                    </div>
                </div>');
         echo('
            </form>
        </div>');

                    /*echo('            
            <!--<form id="artisanoscope-custom-daterange-form"> -->
                <div id="artisanoscope-daterange-filter-container">
                    <!-- <label for="artisanoscope-daterange-filter">Pour une période de dates</label> -->
                </div>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
                <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
                <div>
                <input type="text" name="dates" value="Dates" />
                </div>
            </form>
        </div>');*/
        wp_enqueue_script("customFilters", get_stylesheet_directory_uri().'/assets/js/artisanoscopeWorkshopsCustomFilters.js');
}
add_action( 'woocommerce_before_shop_loop', 'artisanoscope_display_workshops_custom_filters');

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
    // Les dates
    $workshopsDate = get_field('date');

    if( isset($_GET['art'])||isset($_GET['cat'])||(isset($_GET['du'])&&isset($_GET['au'])) ){
        //artisan
        if(isset($_GET['art'])){
            $artisan_query = $_GET['art'];
            if($artisan_query  === $artisan_slug){
                return $visible;
            }else if($artisan_query  === 'all'){
                unset($_GET['art']);
            }
        }
        //catégorie
        if(isset($_GET['cat'])){
            $category_query = $_GET['cat'];
            if(in_array($category_query, $category_slugs)){
                return $visible;
            }else if($category_query  === 'all'){
                unset($_GET['cat']);
            }
        }
        //dates
        if(isset($_GET['du']) && isset($_GET['au'])){
            $start_date = $_GET['du'];
            $end_date = $_GET['au'];
            
            if(check_daterange($start_date, $end_date, $workshopsDate)){
                return $visible;
            }
        }
    }else{
        return $visible;
    }
}
add_filter('woocommerce_product_is_visible', 'artisanoscope_workshops_custom_filters', 10, 2);

function artisanoscope_workshops_custom_date_filter($visible, $productId){
        if(isset($_GET['dates'])){
            $workshopsDate = get_field('date');
            
            $dates_query = $_GET['dates'];
            $split = explode(" - ", $dates_query);;
            $start_date = $split[0];
            $end_date = $split[1];
            $inRange = check_daterange($start_date, $end_date, $workshopsDate);
            
            if($inRange){
                return $visible;
            }else{
                return;
            }
        }else{
            return $visible;
        }
}
//add_filter('woocommerce_product_is_visible', 'artisanoscope_workshops_custom_date_filter', 10, 2);

function check_daterange($start_date_string, $end_date_string, $workshops_date_string)
{
  // Convert to timestamp
  $start_date = strtotime($start_date_string);
  $end_date = strtotime($end_date_string);

  $workshop_date_correct_string = str_replace("/", "-", $workshops_date_string);
  $workshop_date = strtotime($workshop_date_correct_string);

  // Check that user date is between start & end
  return (($workshop_date >= $start_date) && ($workshop_date <= $end_date));
}