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
//Supprimer les filtres Woocommerce par défaut
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//N'afficher que les produits dont les champs requis sont renseignés et dont les dates et horaires sont cohérents:
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);
function artisanoscope_only_display_workshops_with_required_fields($visible, $productId){
    $product = wc_get_product($productId);
    $product_type = get_field("produit_atelier", $productId);
    $format = get_field("format", $productId);
    $start_time = get_field("heure_debut", $productId);
    $end_time = get_field("heure_fin", $productId);
    $address = get_field("lieu", $productId);
    $workshop_date = get_field("date", $productId);
    $course_start_date = get_field("date_debut", $productId);
    $course_end_date = get_field("date_fin", $productId);

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
          // Pour une formation (simple): vérifier la cohérence des dates:
          if($course_start_date >= $course_end_date){
            return false;
          }
          }
          // Pour Atelier & Formation: vérifier la cohérence des horaires:
          if($start_time >= $end_time){
            return false;
          }

        }
    }
    return $visible;
}

//Balise ouvrante de chaque carte
add_action( 'woocommerce_before_shop_loop_item', 'artisanoscope_start_card_div', 0 );
function artisanoscope_start_card_div() {
  echo('<div class="artisan-workshops-card">');
}

//Titre
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
  $format = get_field("format");
  $date = get_field("date");
  $date_debut = get_field("date_debut");
  $date_fin = get_field("date_debut");
  $periodicite = get_field("periodicite"); 

  // Pour les ateliers ponctuels => renseigner la durée d'une session:
    $duree = get_field("duree_session");

  if(empty($duree)){
    if($type =="simple"){
      $heure_debut= get_field("heure_debut");
      $heure_fin= get_field("heure_fin");
      if(!empty($heure_debut)&& !empty($heure_fin)){
        $duree = "".gmdate("G:i", strtotime($heure_fin) - strtotime($heure_debut))."H" ;
      }
    }
  } 

  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    //Produits simples:
    if($type =="simple"){
      if($format ==="ponctuel"){
        echo("<p class='artisan-workshops-card-info'>Le ".$date."</p>");
        echo("<p class='artisan-workshops-card-info'>Durée: ".$duree."</p>");
      }
      if($format ==="abonnement"){
        echo("<p class='artisan-workshops-card-info'>Du ".$date_debut." au ".$date_fin."</p>");
        echo("<p class='artisan-workshops-card-info'>".$periodicite."</p>");
      }

    //Produits variables:
    }
  }

}
//Balise fermante de chaque carte
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_of_product_card', 20 );
function artisanoscope_end_of_product_card() {
  global $product;

  $type = $product->get_type();
  $format = get_field("format");
  if(isset($format)&&!empty($format) && isset($type)&&!empty($type)){
    if($type == "variable"){
      if($format ==="ponctuel"){
        echo("<button>Voir les dates</button>");
      }
      if($format ==="abonnement"){
        echo("<button>Voir les sessions</button>");
      }
    }
  }
  echo('</div>');
}
