<?php
/**
**activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

/*STYLES*/
function theme_enqueue_styles(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // styles custom artisanoscope
    wp_enqueue_style('single-artisan-style', get_stylesheet_directory_uri() .'/custom-css/single-artisan-style.css');
    wp_enqueue_style('content-single-product-style', get_stylesheet_directory_uri() .'/woocommerce/custom-css/content-single-product-style.css');
}

/*HOOKS WOOCOMMERCE*/

// I - FONCTIONS CUSTOM
// FILTRE: les ateliers ne s'affichent dans le catalogue que si les champs ACF requis sont renseignés
function artisanoscope_only_display_workshops_with_required_fields($visible, $productId){
    $product = wc_get_product($productId);
    $date = get_field("date", $productId);
    $startTime = get_field("heure_debut", $productId);
    $endTime = get_field("heure_fin", $productId);
    $address = get_field("lieu", $productId);

    if(empty($date) || empty($startTime) || empty($endTime) || empty($address)) {
      return false; // Si un champ requis est vide, ne pas afficher le produit
    }
    
    // Vérifie également la disponibilité et le prix
    if($product->get_stock_quantity() <= 0 || $product->get_price() <= 0) {
      return false;
    }
    
    return $visible; // Par défaut, le produit est affiché
}
// ACTION: Ajouter la date sous la photo de chaque atelier
function artisanoscope_content_product_display_workshop_date(){
  $date = get_field("date");
  echo("<p class='artisan-workshops-card-info date'>".$date."</p>");
}
// ACTION: Style custom du titre de produit: remplacer les classes Woocommerce par mes classes custom
function artisanoscope_product_content_custom_class_for_title(){
  global $product;
  echo("<h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>");
}
// ACTION: Afficher les horaire pour chaque atelier
function artisanoscope_product_content_display_hours(){
  $startTime = get_field("heure_debut");
  $endTime = get_field("heure_fin");
  echo("<p class='artisan-workshops-card-info'>".$startTime." - ".$endTime."</p>");
}

// II - AJOUT DES FONCTIONS CUSTOM
// FILTRES
// Afficher uniquement les ateliers avec toutes les infos requises
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);

// ACTIONS
//Supprimer les mentions de vente flash
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
// Afficher la date d'atelier
add_action('woocommerce_before_shop_loop_item_title', 'artisanoscope_content_product_display_workshop_date',10);
// Enlever le titre avec son style par défaut
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
// Ajouter le titre avec la classe et le style custom
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_custom_class_for_title',10);
// Ajouter les horaires des ateliers
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_display_hours',10);
// Supprimer les notes des ateliers
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating');
// Supprimer le bouton d'ajout au panier
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
