<?php
// AFFICHAGE DES INPUTS DE FILTRES
function artisanoscope_display_workshops_custom_filters() {
    // I - RÉCUPÉRATION DES TERMES

    // 1 - LES ARTISANS
    /*
    $artisans=[];
    $args = array('post_type' => 'artisan', 'post_status' => 'publish','posts_per_page' => -1);
    $artisansRaw = get_posts($args);
    if ( $artisansRaw) {
        foreach($artisansRaw as $artisan){
            array_push($artisans, $artisan);
        }
    }
    */

    // 2 - LES CATÉGORIES
    $crafts=get_terms('product_cat', array('hide_empty' => true));


    // II - AFFICHAGE DES INPUTS DE FILTRES

    // OUVERTURE DU FORMULAIRE

   /* echo('<div id="artisanoscope-custom-filters-container"><form id="artisanoscope-custom-filters-form" method="get" style="display:flex;justify-content:space-around;">');*/
    echo('<div id="artisanoscope-custom-filters-container" style="display:flex; justify-content:space-around;">');

   
    // 1 - LES CATÉGORIES
    echo('<div id="artisanoscope-craft-filter-container">
        <select name="cat" id="artisanoscope-craft-filter">
            <option value="all">Savoir-faire</option>');
            foreach ($crafts as $craft){
                if (isset($_GET['cat']) && $_GET['cat'] === $craft->slug){
                    echo('<option value="'.$craft->slug.'" selected>'.$craft->name.'</option>');
                }else{
                    if($craft->name != "Frais ou remises" && $craft->name != "Applique un tarif enfant"&& $craft->name != "Frais additionnel"){
                        echo('<option value="'.$craft->slug.'">'.$craft->name.'</option>');
                    }
                }
            }
    echo('</select></div>');  
        
    // 2 - LES PÉRIODES DE DATES - avec inputs html
    
    if (isset($_GET['du'])){
        $start = $_GET['du'];
    }else{
        $start = null;
    }
    if (isset($_GET['au'])){
        $end = $_GET['au'];
    }else{
        //$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start)). " + 365 day"));
        $end = null;
    }
    if(isset($_GET['du'])&&!empty($_GET['du'])&&isset($_GET['au'])&&!empty($_GET['au'])){
        echo('<div id="artisanoscope-daterange-filter-container">
            <button id="artisanoscope-daterange-filter-button" type="button">'.date("d/m/Y", strtotime ($_GET['du'])).' - '.date("d/m/Y", strtotime ($_GET['au'])).'</button>
            <div id="artisanoscope-daterange-toggle-zone" class="hide" style="display:flex;">
                <input type="date" id="artisanoscope-daterange-start" name="du" value="'.$start.'" />
                <input type="date" id="artisanoscope-daterange-end"  name="au" value="'.$end.'" />
            </div>
            <div id="artisanoscope-daterange-warning-missing-date" class="atelier-option-warning hide">Veuillez saisir une date de début</div>
            <div id="artisanoscope-daterange-warning-incoherent-date" class="atelier-option-warning hide">Veuillez saisir une date de début antérieure à la date de fin</div>
        </div>');
    }else{
        $date = new DateTimeImmutable();
        
        $today = date('Y-m-d');
        $tomorrow =  date('Y-m-d', strtotime($today . ' +1 day'));

        echo('<div id="artisanoscope-daterange-filter-container">
            <button id="artisanoscope-daterange-filter-button" type="button">Dates</button>
            <div id="artisanoscope-daterange-toggle-zone" class="hide" style="display:flex;">
                <input type="date" id="artisanoscope-daterange-start" name="du" value="'.$today.'" />
                <input type="date" id="artisanoscope-daterange-end"  name="au" value="'.$tomorrow.'" />
            </div>
            <div id="artisanoscope-daterange-warning-missing-date" class="atelier-option-warning hide">Veuillez saisir une date de début</div>
            <div id="artisanoscope-daterange-warning-incoherent-date" class="atelier-option-warning hide">Veuillez saisir une date de début antérieure à la date de fin</div>
        </div>');
    }



    // 2 - LES PÉRIODES DE DATES - avec daterangepicker
    /*
    echo('<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />');
    */

    /*
    echo('<div id="artisanoscope-daterange-filter-container">

        <div id="artisanoscope-daterange-toggle-zone">
        <input type="text" name="daterange" id="artisanoscope-daterange"/>
        </div>
    </div>');
    */
        /*
            <script>
        $(function() {
          $(`input[name="daterange"]`).daterangepicker({
          }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format(`YYYY-MM-DD`) + ` to ` + end.format(`YYYY-MM-DD`));
            console.log( start + " to " + end);
          });
          $(`#daterange`).trigger(`show.daterangepicker`);
        });
        </script>
    */

    // 5 - PLACES DISPONIBLES
    if(isset($_GET['places'])&&!empty($_GET['places'])){
        echo('<div id="artisanoscope-availabilities-filter-container">');
        if(intval($_GET['places'])>1){
            echo('<button id="artisanoscope-availabilities-filter-button" type="button">'.$_GET['places'].' places disponibles</button>');
        }else{
            echo('<button id="artisanoscope-availabilities-filter-button" type="button">'.$_GET['places'].' place disponible</button>');
        }
        echo('<div id="artisanoscope-availabilities-toggle-zone" class="hide">
                    <input type="number" name="places" id="artisanoscope-availabilities-filter" min="1" max="50" value="'.$_GET['places'].'">
                </div>
            </div>');
    }else{
        echo('<div id="artisanoscope-availabilities-filter-container">
                <button id="artisanoscope-availabilities-filter-button" type="button">Nombre de places disponibles</button>
                <div id="artisanoscope-availabilities-toggle-zone" class="hide">
                    <input type="number" name="places" id="artisanoscope-availabilities-filter" min="1" max="50" value="1">
                </div>
            </div>');
    }

    //Container enfants et débutants - début
    echo('<div id="artisanoscope-kids-and-beginners-container">');
    // 3 - ADAPTÉ AUX ENFANTS
    echo('<div id="artisanoscope-age-filter-container">');
    if(isset($_GET['age'])){
        echo(' <input type="checkbox" name="age" id="artisanoscope-age-filter-checkbox" checked>');
    }else{
        echo(' <input type="checkbox" name="age" id="artisanoscope-age-filter-checkbox">');
    }
    echo('<label> Adapté aux enfants</label></div>');


    // 4 - DÉBUTANTS ACCEPTÉS
    echo('<div id="artisanoscope-beginner-friendly-filter-container">');
    if(isset($_GET['debutant'])){
        echo('<input type="checkbox"  name="debutant" id="artisanoscope-beginner-friendly-filter-checkbox" checked>');
    }else{
        echo('<input type="checkbox"  name="debutant" id="artisanoscope-beginner-friendly-filter-checkbox">');
    }
    echo('<label> Débutants acceptés</label></div>');
    echo('</div>');
    //Container enfants et débutants - fin
    // 6 - RÉINITIALISER LES FILTRES
    echo('<div id="artisanoscope-reset-all-filters-container">
            <input type="button" id="artisanoscope-reset-all-filters-button" value="Réinitialiser" name="reset"/>
        </div>');    


    // FERMETURE DU FORMULAIRE

    //echo('</form></div>');
    echo('</div>');

    // 3 - SCRIPT
    //wp_enqueue_script("customFilters", get_stylesheet_directory_uri().'/assets/js/artisanoscopeWorkshopsCustomFilters.js');
    wp_enqueue_script("customFilters", get_stylesheet_directory_uri().'/assets/js/artisanoscopeNewFilters.js');
}
add_action( 'woocommerce_before_shop_loop', 'artisanoscope_display_workshops_custom_filters');


function artisanoscope_workshops_custom_filters($visible, $productId){
    $product = wc_get_product( $productId );
    $type = $product->get_type();

    $filter_active = false;
    $at_least_one_filter_satisfied = false;

    // I - CATEGORIE
    if (isset($_GET['cat']) && $_GET['cat'] !== 'all') {
        $filter_active = true;
        $categories = get_the_terms( $productId, 'product_cat' );
        $category_slugs = [];
        if($categories){
            foreach($categories as $category){
                array_push($category_slugs, $category->slug);
            }
        }
        $category_query = $_GET['cat'];
        if (in_array($category_query, $category_slugs)) {
            $at_least_one_filter_satisfied = true;
        }
    }elseif( isset($_GET['cat']) && $_GET['cat'] === 'all'){
        unset($_GET['cat']);
    }

    // II - DATES
    if (isset($_GET['du']) && isset($_GET['au']) && !empty($_GET['du']) && !empty($_GET['au'])) {
        $filter_active = true;
        $show_in_date = false;

        $start_date = $_GET['du'];
        $end_date = $_GET['au'];

        if($start_date === null || $end_date === null) {
            unset($_GET['du']);
            unset($_GET['au']);
        }

        // En cas de produit variable, vérifier si au moins une date de variation est dans l'intervalle demandé
        if($type === "variable"){
            $variations = $product->get_available_variations();        
            foreach($variations as $variation){
                $variation_id = $variation['variation_id'];
                $variation_date = get_field('date', $variation_id);
        
                if(check_date_is_in_range($start_date, $end_date, $variation_date)) {
                    $show_in_date = true;
                    // Arrêter la boucle dès que je trouve une variation qui correspond à la demande
                    break;
                }
            }
        }elseif($type === "simple"){
            $format = get_field('prod_format', $productId);
            if($format === "ponctuel"){
                $workshopsDate = get_field('prod_date', $productId);
                if(is_null($workshopsDate)||empty($workshopsDate)){
                    $show_in_date = false;
                }else{
                    $show_in_date = check_date_is_in_range($start_date, $end_date, $workshopsDate);
                }
            }elseif($format === "abonnement"){
                $courseStart = get_field('prod_date_debut', $productId);
                if(is_null($courseStart)||empty($courseStart)){
                    $show_in_date = false;
                } else{
                    $show_in_date =check_date_is_in_range($start_date, $end_date, $courseStart);
                }
            }
        }
        if ($show_in_date) {
            $at_least_one_filter_satisfied = true;
        }
    }elseif(isset($_GET['du']) && isset($_GET['au'])&&($_GET['du'] === null || $_GET['au'] === null)){
        unset($_GET['du']);
        unset($_GET['au']);
    }

    // III - AGE
    if (isset($_GET['age']) && $_GET['age'] === 'on') {
        $filter_active = true;
        if (isset($_GET['age']) && $_GET['age'] === 'on') {
            $ages = get_field("prod_age", $productId);
            $show_in_age = in_array("enfant", $ages);
        }
        if ($show_in_age) {
            $at_least_one_filter_satisfied = true;
        }
    }

    // IV - DEBUTANT
    if (isset($_GET['debutant']) && $_GET['debutant'] === 'on') {
        $filter_active = true;
        if (isset($_GET['debutant']) && $_GET['debutant'] === 'on') {
            $levels = get_field("prod_debutant", $productId);
            $show_in_beginner = in_array("debutant", $levels);
        }
        if ($show_in_beginner) {
            $at_least_one_filter_satisfied = true;
        }
    }
    
    // V - PLACES DISPONIBLES
    if(isset($_GET['places']) && !empty($_GET['places']) && is_int(intval($_GET['places']))){
        $filter_active = true;
        $show_in_participants = false;
        if($type ==="simple"){
            $show_in_participants = ($product->get_stock_quantity() >= $_GET['places']);
        }elseif($type ==="variable"){
            //**
            $variations = $product->get_available_variations();        
            foreach($variations as $variation){
                if($variation['availabilities'] >= $_GET['places']) {
                    $show_in_participants = true;
                    // Arrêter la boucle dès que je trouve une variation qui correspond à la demande
                    break;
                }
            }
            //** 
            //$show_in_participants = false;
        }else{
            $show_in_participants = false; 
        }
        if ($show_in_participants) {
            $at_least_one_filter_satisfied = true;
        }
    }

    // V - REINITIALISER
    if(isset($_GET['reset'])&&$_GET['reset']==='Réinitialiser'){
        unset($_GET['debutant']);
        unset($_GET['age']);
        unset($_GET['du']);
        unset($_GET['au']);
        unset($_GET['cat']);
    }

    // Si aucun filtre n'est actif, retournez $visible, sinon retournez true si au moins un filtre actif est satisfait
    return !$filter_active || $at_least_one_filter_satisfied ? $visible : false;
}
add_filter('woocommerce_product_is_visible', 'artisanoscope_workshops_custom_filters', 10,2);

function check_date_is_in_range($start_date_string, $end_date_string, $workshops_date_string)
{
  // Convertion en timestamp
  $start_date = strtotime($start_date_string);
  $end_date = strtotime($end_date_string);

  $workshop_date_correct_string = str_replace("/", "-", $workshops_date_string);
  $workshop_date = strtotime($workshop_date_correct_string);

  // Vérifier que la date de l'atelier est comprise entre les deux dates de requêtes
  //return (($workshop_date >= $start_date) && ($workshop_date <= $end_date));
  if(($workshop_date >= $start_date) && ($workshop_date <= $end_date)){
    return true;
  }else{
    return false;
  }
}