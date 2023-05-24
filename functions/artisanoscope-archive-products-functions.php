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
  //ou
  //$product = wc_get_product( $productId ); si param

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
  if($type === "simple"){
    if($format === "ponctuel"){
      $dates .= " date-".get_field("prod_date");
    }elseif($format === "abonnement"){
      $dates .= " date-".get_field("prod_date_debut");
    }
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
  if($type === "simple"){
    $stock .= "stock-".$product->get_stock_quantity();
  }elseif($type === "variable"){
    foreach($variations as $variation){

 //Récupérer l'objet de variation WooCommerce à partir de l'ID de la variation
        $variation_id = $variation['variation_id'];
        $variation_obj = wc_get_product($variation_id);

        // Vérifier si la variation existe et si elle est de type "variation"
        if ($variation_obj && $variation_obj->is_type('variation')) {
            // Obtenez la quantité de stock sous la forme d'un entier
            $stock_quantity = $variation_obj->get_stock_quantity();

            // Affichez la quantité de stock
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

  $date="";
  $format = get_field("prod_format");
  //test
  if($format == "ponctuel"){
    $date=get_field("prod_date");
  }elseif($format == "abonnement"){
    $date=get_field("prod_date_debut");
  }
  //test
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

  //Produits simples
  if(isset($type)&&!empty($type)&&$type==="simple"){
    //Atelier
    if(isset($format)&&!empty($format)&&$format==="ponctuel"){
      artisanoscope_simple_workshop_infos();
    //Formation
    }elseif(isset($format)&&!empty($format)&&$format==="abonnement"){
      artisanoscope_simple_course_infos();
    }
  }elseif(isset($type)&&!empty($type)&&$type==="variable"){
    artisanoscope_variable_workshop_infos($product);
  }

}

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

// Bouton pour les produits variables
//add_action('woocommerce_shop_loop_item_title', 'artisanoscope_variable_workshop_button',10);
function artisanoscope_variable_workshop_button(){
  global $product;
  $type = $product->get_type();
  $format = get_field("prod_format");
  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    if($type == "variable"){
      if($format ==="ponctuel"){
        echo("<div class='artisanoscope-product-info-line see-dates'><p class='artisanoscope-variable-workshop-dates-btn'>Voir les dates</p>".svg("arrow"))."</div>";
      }
    }
  }
}


//Balise fermante de chaque carte
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_of_product_card', 20 );
function artisanoscope_end_of_product_card() {
  echo('</div>');
  echo('</div>');
}
