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


//test : classe dynamiques pour filtrage des produits dans le catalogue
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

// N'afficher que les produits dont les champs requis sont renseignés et dont les dates et horaires sont cohérents:
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);
function artisanoscope_only_display_workshops_with_required_fields($visible, $productId){
    $product = wc_get_product($productId);
    $product_type = get_field("prod_physique_atelier", $productId);
    $format = get_field("prod_format", $productId);
    $start_time = get_field("prod_heure_debut", $productId);
    $end_time = get_field("prod_heure_fin", $productId);
    $address = get_field("prod_lieu", $productId);
    $workshop_date = get_field("prod_date", $productId);
    $course_start_date = get_field("prod_date_debut", $productId);
    $course_end_date = get_field("prod_date_fin", $productId);

    // Requis pour produits simples:

    //Pour les produits simples (physiques ou ateliers & formation)
    //Pour les produits variables, la vérification se fera pour chaque option, dans artisanoscope-single-product-functions.php
    $type = $product->get_type();
    if($type=="simple"){
      // Vérifier la disponibilité et le prix
        if($product->get_stock_quantity() <= 0 || $product->get_price() <= 0){
            return false;
        }
        //Un atelier (ponctuel ou formation) doit renseigner au moins les horaires et le lieu
        if($product_type=="Atelier"){
          // Pour un atelier (simple), la date doit être renseignée
          if($format == "ponctuel"){
            if(empty($workshop_date)||empty($start_time)||empty($end_time)||empty($address)){
              return false;
            }
          }
          // Pour une formation (simple), renseigner au moins les dates de début et de fin
          if($format == "abonnement"){
            if(empty($course_start_date)||empty($course_end_date)){
              return false;
            }
            
            // A CORRIGER - Ne fonctionne pas: Pour une formation (simple): vérifier la cohérence des dates:
            if(strtotime($course_start_date)<strtotime(date('Y/m/d H:i:s'))||strtotime($course_start_date) >= strtotime($course_end_date)){
              return false;
            }
          }
          // Pour Atelier & Formation: vérifier la cohérence des horaires:
          if(strtotime($start_time) >= strtotime($end_time)){
            return false;
          }
        }
    }
    return $visible;
}

//Balise ouvrante de chaque carte, classes dynamiques pour le filtrage
add_action( 'woocommerce_before_shop_loop_item', 'artisanoscope_start_card_div', 0 );
function artisanoscope_start_card_div() {
  echo('<div class="artisan-workshops-card '.artisanoscope_dynamic_class().'">');
}

// Adapté aux enfants
add_action( 'woocommerce_before_shop_loop_item_title', 'artisanoscope_kid_friendly_notice', 10 );
function artisanoscope_kid_friendly_notice(){
  $age = get_field("prod_age");
  if(isset($age)&&!empty($age)&&in_array("enfant",$age)){
    echo("<p style='color: #598562; text-align:center; margin-top:0.5em;'>Adapté aux enfants</p>");
  }
}

// Titre
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_custom_class_for_title',10);
function artisanoscope_product_content_custom_class_for_title(){
  global $product;

  //Ajouter mon titre avec la classe custom
  echo("<h2 class='artisan-workshops-card-title'>".$product->get_name()."</h2>");
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

  //Afficher la date
  if(isset($date) && !empty($date)){
    echo("<div style='display:flex; color:lightslategray' class='artisanoscope-product-info-line'>".svg("date")."<p>Le ".$date."</p></div>");
  }
  //Afficher la durée
  if(isset($duration) && !empty($duration)){
    echo("<div style='display:flex; color:lightslategray' class='artisanoscope-product-info-line'>".svg("duration")."<p>".format_duration($duration)."H</p></div>");
  }
}
// Infos de formation
function artisanoscope_simple_course_infos(){
  $date_debut = get_field("prod_date_debut");
  $date_fin = get_field("prod_date_fin");
  $periodicite = get_field("prod_periodicite"); 
  if(isset($periodicite) && !empty($periodicite)){
    echo("<div style='display:flex; color:lightslategray' class='artisanoscope-product-info-line'>".svg("recurrence")."<p>".$periodicite."</p></div>");
  }
  if(isset($date_debut) && !empty($date_debut)&& isset($date_fin) && !empty($date_fin)){
    echo("<div style='display:flex; color:lightslategray' class='artisanoscope-product-info-line'>".svg("date")."<p>".$date_debut." - ".$date_fin."</p></div>");
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
    echo("<div style='display:flex; color:lightslategray' class='artisanoscope-product-info-line'>".svg("duration")."<p>".format_duration($duration)."H</p></div>");
  }
}


function artisanoscope_simple_product_data_display(){
  global $product;
  $type = $product->get_type();
  $format = get_field("prod_format");
  $date = get_field("prod_date");
  $date_debut = get_field("prod_date_debut");
  $date_fin = get_field("prod_date_fin");
  $periodicite = get_field("prod_periodicite"); 

  //Durée d'une session
  $heure_debut = get_field("prod_heure_debut");
  $heure_fin = get_field("prod_heure_fin");
  $duree = "";
  if(!empty($heure_debut)&& !empty($heure_fin)){
    $duree .= gmdate("G:i", strtotime($heure_fin) - strtotime($heure_debut)) ;
  }
  
  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    //Produits simples:
    if($type =="simple"){
      if($format ==="ponctuel"){
        echo("<p class='artisan-workshops-card-info'>Le ".$date."</p>");
        echo("<p class='artisan-workshops-card-info'>".$duree."</p>");
      }
      if($format ==="abonnement"){
        echo("<p class='artisan-workshops-card-info'>Du ".$date_debut." au ".$date_fin."</p>");
        echo("<p class='artisan-workshops-card-info'>".$periodicite."</p>");
      }
    }
  }
}
// Bouton pour les produits variables
add_action('woocommerce_after_shop_loop_item_title', 'artisanoscope_variable_workshop_button',10);
function artisanoscope_variable_workshop_button(){
  global $product;
  $type = $product->get_type();
  $format = get_field("prod_format");
  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    if($type == "variable"){
      if($format ==="ponctuel"){
        echo("<p style='color: #598562; text-align:center; border: 1px solid #598562; border-radius:5px; padding:0.5em; margin-top:0.5em;'>Voir les dates</p>");
      }
    }
  }
}


//Balise fermante de chaque carte
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_of_product_card', 20 );
function artisanoscope_end_of_product_card() {
  echo('</div>');
}
