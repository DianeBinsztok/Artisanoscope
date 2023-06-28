<?php
// SUPPRIMER
// Supprimer 
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb');
// Supprimer le nombre de résultats
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
// Supprimer le fil d'Ariane
remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices');
// Supprimer le titre avec son style par défaut
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
// Supprimer les notes des ateliers
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating');
// Supprimer le bouton d'ajout au panier
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
//Supprimer les mentions de vente flash
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
// Supprimer les filtres Woocommerce par défaut
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


//Classes dynamiques pour filtrage des produits dans le catalogue
function artisanoscope_dynamic_class(){
  global $product;
  $dynamic_class = "";
  $id = $product->get_id();
  $type = $product->get_type();
  $format = get_field("prod_format");

  $variations = "";
  if($type === "variable"){
    $variations = $product->get_available_variations();        
  }
  //CATEGORIES
  $categories = "";
  $category_array = get_the_terms( $id, 'product_cat' );
  foreach($category_array as $category){
    $categories .= " ".$category->slug;
  }
  //DATE(S)
  $dates = "";
  //Si le produit est simple, récupérer le champs ACF de date
  if($type === "simple"){
    if($format === "ponctuel"){
      $dates .= " date-".get_field("prod_date");
    }elseif($format === "abonnement"){
      $dates .= " date-".get_field("prod_date_debut");
    }
  //Si le produit est variable, récupérer toutes les variations de date
  }elseif($type === "variable"){
    foreach($variations as $variation){
      $variation_id = $variation['variation_id'];

      /*EN ATTENTE DES ATTRIBUTS DU LOGICIEL DE CAISSE: */

      /*CAS 1 -  Si nom de variation => $variation["attributes"]["attribute_pa_date"]*/
      /*$variation_date = $variation["attributes"]["attribute_pa_date"];*/

      /*CAS 2 -  Si champs custom de variation => $variation["date"]*/
      $variation_date = $variation['date'];
      $dates .=  " date-".$variation_date;

    }
  }
  //ENFANTS
  $kid_friendly = "";
  $ages = get_field("prod_age");
  if(isset($ages)&&!empty($ages)){
    if(in_array("enfant", $ages)){
      $kid_friendly = "kid-friendly";
    }
  }
  //DÉBUTANTS
  $beginner_friendly = "";
  $niveaux = get_field("prod_debutant");
  if(isset($niveaux)&&!empty($niveaux)){
    if(in_array("debutant", $niveaux)){
      $beginner_friendly = "beginner-friendly";
    }
  }


  //STOCK
  $stock="";
  //Pour un produit simple, récupérer le stock
  if($type === "simple"){
    $stock .= "stock-".$product->get_stock_quantity();

  //Pour un produit variable, récupérer le stock de chaque variation
  }elseif($type === "variable"){
    foreach($variations as $variation){

        $variation_id = $variation['variation_id'];
        $variation_obj = wc_get_product($variation_id);

        // Vérifier si la variation existe et si elle est de type "variation"
        if ($variation_obj && $variation_obj->is_type('variation')) {
            // La quantité de stock sous la forme d'un entier
            $stock_quantity = $variation_obj->get_stock_quantity();

            // Ajouter la classe "stock-..."
            $stock .= " stock-".$stock_quantity;
        }
    }
  }
 $dynamic_class.= $categories." ".$dates." ".$kid_friendly." ".$beginner_friendly." ".$stock;
  return $dynamic_class;
}

//Balise ouvrante de chaque carte, classes dynamiques pour le filtrage
add_action( 'woocommerce_before_shop_loop_item', 'artisanoscope_start_card_div', 0 );
function artisanoscope_start_card_div() {
  echo('<div class="artisanoscope-workshops-card '.artisanoscope_dynamic_class().'">');
}

// Adapté aux enfants
add_action( 'woocommerce_before_shop_loop_item_title', 'artisanoscope_kids_and_beginners_notice', 10 );
function artisanoscope_kids_and_beginners_notice(){
  echo("<div class='artisanoscope-product-info-content'>");

  $age = get_field("prod_age");
  $level = get_field("prod_debutant");
  if((isset($age)&&!empty($age)&&in_array("enfant",$age)||(isset($level)&&!empty($level)&&in_array("debutant",$level)))){
    echo("<div class='artisanoscope-workshops-card-extras'>");
    if(isset($age)&&!empty($age)&&in_array("enfant",$age)){
      echo("<div class='artisanoscope-workshops-card-extra'>".svg("kid_friendly_rounded")."</div>");
    }
    if(isset($level)&&!empty($level)&&in_array("debutant",$level)){
      echo("<div class='artisanoscope-workshops-card-extra'>".svg("beginners_accepted_rounded")."</div>");
    }
    echo("</div>");
  }


}

// Titre
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_custom_class_for_title',10);
function artisanoscope_product_content_custom_class_for_title(){
  global $product;
  $age = get_field("prod_age");
  $level = get_field("prod_debutant");
  //Ajouter mon titre avec la classe custom
  if(empty($level)&&empty($age)){
    echo("<h2 class='artisanoscope-workshops-card-title extra-margin'>".$product->get_name()."</h2>");
  }else{
    echo("<h2 class='artisanoscope-workshops-card-title'>".$product->get_name()."</h2>");
  }

}

// Infos dispatch
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_dispatch_infos_if_simple_or_variable',10);
function artisanoscope_dispatch_infos_if_simple_or_variable(){
  global $product;
  $type = $product->get_type();
  $format = get_field("prod_format");

  //1 - Produits simples
  if(isset($type)&&!empty($type)&&$type==="simple"){

    //Atelier simple
    if(isset($format)&&!empty($format)&&$format==="ponctuel"){
      artisanoscope_simple_workshop_infos();

    //Formation
    }elseif(isset($format)&&!empty($format)&&$format==="abonnement"){
      artisanoscope_simple_course_infos();
    }

  //2 - Produits variables
  }elseif(isset($type)&&!empty($type)&&$type==="variable"){
    artisanoscope_variable_workshop_infos($product);
  }

}

// Afficher la durée d'une session au bon format - utilisé par les 3 fonctions suivantes:
function format_duration($hour){

  $minutes = substr($hour, -3);
  if($minutes === ":00"){
    return str_replace(":00", "", $hour);
  }else{
    return $hour;
  }
}

// Infos d'atelier simple
function artisanoscope_simple_workshop_infos(){
  $date = get_field("prod_date");
  //Durée d'une session
  $start_hour = get_field("prod_heure_debut");
  $end_time = get_field("prod_heure_fin");
  $duration = "";
  if(isset($start_hour) && !empty($start_hour)&& isset($end_time) && !empty($end_time)){
    $duration .= gmdate("G:i", strtotime($end_time) - strtotime($start_hour));
  }
  //Afficher la durée
  if(isset($duration) && !empty($duration)){
    echo("<div class='artisanoscope-product-info-line'>".svg("duration")."<p>".format_duration($duration)."H</p></div>");
  }
  //Afficher la date
  if(isset($date) && !empty($date)){
    echo("<div class='artisanoscope-product-info-line'>".svg("date")."<p>Le ".$date."</p></div>");
  }
}
// Infos de formation
function artisanoscope_simple_course_infos(){
  $date_debut = get_field("prod_date_debut");
  $date_fin = get_field("prod_date_fin");
  $periodicite = get_field("prod_periodicite"); 

  /*
    //Durée d'une session
  $start_hour = get_field("prod_heure_debut");
  $end_time = get_field("prod_heure_fin");
  $duration = "";
  if(isset($start_hour) && !empty($start_hour)&& isset($end_time) && !empty($end_time)){
    $duration .= gmdate("G:i", strtotime($end_time) - strtotime($start_hour));
  }
  //Afficher la durée
  if(isset($duration) && !empty($duration)){
    echo("<div class='artisanoscope-product-info-line'>".svg("duration")."<p>".format_duration($duration)."H</p></div>");
  }
  */
  if(isset($periodicite) && !empty($periodicite)){
    echo("<div class='artisanoscope-product-info-line'>".svg("recurrence")."<p>".$periodicite."</p></div>");
  }
  /*
    if(isset($date_debut) && !empty($date_debut)&& isset($date_fin) && !empty($date_fin)){
    echo("<div class='artisanoscope-product-info-line'>".svg("date")."<p>".$date_debut." - ".$date_fin."</p></div>");
  }
  */
  if(isset($date_debut) && !empty($date_debut)&& isset($date_fin) && !empty($date_fin)){
    echo("<div class='artisanoscope-product-info-line'>".svg("date")."<p> Jusqu'au ".$date_fin."</p></div>");
  }
}
// Infos d'atelier variable
function artisanoscope_variable_workshop_infos($product){
  $variations = $product->get_available_variations();
  $duration = "";
  $start_hour = $variations[0]["start_hour"];
  $end_hour = $variations[0]["end_hour"];
  if(isset($start_hour)&&!empty($start_hour)&&isset($end_hour)&&!empty($end_hour)){
    $duration .= gmdate("G:i", strtotime($end_hour) - strtotime($start_hour));
    echo("<div class='artisanoscope-product-info-line'>".svg("duration")."<p>".format_duration($duration)."H</p></div>");
    echo("<div class='artisanoscope-product-info-line'>".svg("date")."<p>Voir les dates</p></div>");
  }
}


//Balise fermante de chaque carte
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_of_product_card', 20 );
function artisanoscope_end_of_product_card() {
  /*TEST POST-META*/

  // I - reference_date
  $reference_date = get_post_meta(get_the_ID(), "reference_date", true );
  echo("reference_date=>");
  var_dump($reference_date);
  echo("en date=>");
  $date_string = gmdate("d/m/Y", intval($reference_date));
  var_dump($date_string);

  //  II - imminence
  $imminence = get_post_meta(get_the_ID(), "imminence", true );
  echo("imminence=>");
  var_dump($imminence);
  

  /*TEST POST-META*/

  echo('</div>');
  echo('</div>');
}

//Supprimer les étoiles de note dans les vignettes de produit
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );





/*TEST - CETTE SECTION NE FONCTIONNE PAS*/
//Ordonner les ateliers et formations par leur date, du plus imminent au plus éloigné dans le temps
function artisanoscope_display_workshops_and_courses_chronologically($args){
  global $post;
  $product = $post->wc_get_product($post->ID);


  $type = $product->get_type();
  $format = get_field("prod_format");

  //Les arguments de la requête
  $args['orderby'] = 'meta_value';
  $args['order'] = 'ASC';

  //Pour un atelier simple: se référer à la date
  if($type == "simple"){
    if($format === "ponctuel"){
      $date= get_field("prod_date");
      $args['meta_key'] =  $date;

    }elseif($format === "abonnement"){
      $date_debut = get_field("prod_date_debut");
      $args['meta_key'] =  $date_debut;
    }
  //Pour une formation: se référer à la date de début
  }elseif($type == "variable"){
  //Pour un atelier variable: se référer à la date la plus imminente parmis les dates des variations
    $variation = $product->get_available_variations();
    echo("test");
    var_dump($variation);
  }
  return $args;
}
//add_filter( 'woocommerce_get_catalog_ordering_args', 'artisanoscope_display_workshops_and_courses_chronologically' );


function artisanoscope_archive_product_order_by_date($q){
	$meta_query = $q->get('meta_query');
	$meta_query[] = array(
		'key' => 'date',
		'value' => date('Y/m/d'),
    /*'value' => date('Y-m-d'),*/
		'compare' => '>=',
		'type' => 'DATE'
	);
	$q->set('meta_query', $meta_query);
	$q->set('orderby', 'meta_value');
	$q->set('meta_key', 'prod_date');
	$q->set('order', 'ASC');
}
//add_filter( 'woocommerce_product_query', 'artisanoscope_archive_product_order_by_date' );
/*FIN DE SECTION QUI NE FONCTIONNE PAS*/