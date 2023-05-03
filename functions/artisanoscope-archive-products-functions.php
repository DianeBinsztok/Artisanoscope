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

// N'afficher que les produits dont les champs requis sont renseignés et dont les dates et horaires sont cohérents:

//add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);
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
            
            // A CORRIGER: Pour une formation (simple): vérifier la cohérence des dates:
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

//Balise ouvrante de chaque carte
add_action( 'woocommerce_before_shop_loop_item', 'artisanoscope_start_card_div', 0 );
function artisanoscope_start_card_div() {
  echo('<div class="artisan-workshops-card" style="justify-content:space-between;">');
}

add_action( 'woocommerce_before_shop_loop_item', 'tests', 0 );
function tests(){

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

// Infos
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_card_display_date_if_workshop_simple',10);
function artisanoscope_product_card_display_date_if_workshop_simple($productId){
  global $product;
  $type = $product->get_type();
  $format = get_field("prod_format");
  $date = get_field("prod_date");
  $date_debut = get_field("prod_date_debut");
  $date_fin = get_field("prod_date_fin");
  $periodicite = get_field("prod_periodicite"); 

  /*
  if(empty($duree)){
    if($type =="simple"){
      $heure_debut= get_field("heure_debut");
      $heure_fin= get_field("heure_fin");
      if(!empty($heure_debut)&& !empty($heure_fin)){
        $duree = "".gmdate("G:i", strtotime($heure_fin) - strtotime($heure_debut))."H" ;
      }
    }
  } 
  */
  
  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    //Produits simples:
    if($type =="simple"){
      if($format ==="ponctuel"){
        echo("<p class='artisan-workshops-card-info'>Le ".$date."</p>");
      }
      if($format ==="abonnement"){
        echo("<p class='artisan-workshops-card-info'>Du ".$date_debut." au ".$date_fin."</p>");
        echo("<p class='artisan-workshops-card-info'>".$periodicite."</p>");
      }
    }
  }
}

// Bouton pour les produits variables
add_action('woocommerce_after_shop_loop_item_title', 'artisanoscope_options_button_if_variable',10);
function artisanoscope_options_button_if_variable(){
  global $product;

  $type = $product->get_type();
  $format = get_field("prod_format");

  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    if($type == "variable"){
        echo("<p style='color: white; text-align:center; background-color: #598562 ;border: 1px solid #598562; border-radius:5px; padding:0.5em; margin-top:0.5em;'>Voir les dates</p>");
    }
  }
}

//Balise fermante de chaque carte
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_of_product_card', 20 );
function artisanoscope_end_of_product_card() {
  echo('</div>');
}
