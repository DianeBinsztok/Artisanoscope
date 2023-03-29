<?php
// ARCHIVE-PRODUCT.PHP => catalogue des ateliers

// I - FONCTIONS CUSTOM

// 0 - Vérifier que tous les champs nécessaires sont remplis
function artisanoscope_check_for_required_fields($productId){
    $product = wc_get_product($productId);
    $date = get_field("date", $productId);
    $startTime = get_field("heure_debut", $productId);
    $endTime = get_field("heure_fin", $productId);
    $address = get_field("lieu", $productId);

    $today = date("d/m/Y");

    // Vérifier que les champs obligatoires sont tous renseignés
    if(empty($date) || empty($startTime) || empty($endTime) || empty($address) || strtotime($date)<strtotime($today)) {
      return false;
    }

    // Vérifier que les horaires sont cohérents
    if($startTime >= $endTime) {
      return false;
    }
    // Vérifier la disponibilité et le prix
    if($product->get_stock_quantity() < 0 || $product->get_price() <= 0) {
      return false;
    }
    return true;// Par défaut, le produit est affiché
}
// 1 - FILTRE: Afficher uniquement les ateliers avec toutes les infos requises
function artisanoscope_only_display_workshops_with_required_fields($visible, $productId){
  /*
    $product = wc_get_product($productId);
    $date = get_field("date", $productId);
    $startTime = get_field("heure_debut", $productId);
    $endTime = get_field("heure_fin", $productId);
    $address = get_field("lieu", $productId);

    // Vérifier que les champs obligatoires sont tous renseignés
    if(empty($date) || empty($startTime) || empty($endTime) || empty($address)) {
      return false;
    }
    // Vérifier que les horaires sont cohérents
    if($startTime >= $endTime) {
      return false;
    }
    // Vérifier la disponibilité et le prix
    if($product->get_stock_quantity() < 0 || $product->get_price() <= 0) {
      return false;
    }
    */
    
    /*$visible = artisanoscope_check_for_required_fields($productId);
    return $visible; */
    return artisanoscope_check_for_required_fields($productId);
}
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);

// 2 - FILTRE: Ordonner les ateliers par date
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
	$q->set('meta_key', 'date');
	$q->set('order', 'ASC');
}
add_filter( 'woocommerce_product_query', 'artisanoscope_archive_product_order_by_date' );

// 3 - ACTION: Ouvrir la div avec la classe custom (pour le style au survol)
function artisanoscope_start_card_div() {
  echo('<div class="artisan-workshops-card">');
}
add_action( 'woocommerce_before_shop_loop_item', 'artisanoscope_start_card_div', 0 );

// 4 - ACTION: Afficher la date d'atelier avant le nom
function artisanoscope_content_product_display_workshop_date(){
  $date = get_field("date");
  echo("<p class='artisan-workshops-card-info date'>".$date."</p>");
}
add_action('woocommerce_before_shop_loop_item_title', 'artisanoscope_content_product_display_workshop_date',10);

// 5 - ACTION: Ajouter le titre avec la classe et le style custom
function artisanoscope_product_content_custom_class_for_title(){
    global $product;
    //Ajouter mon titre avec la classe custom
    echo("<h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>");
}
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_custom_class_for_title',10);

// 6 - ACTION: Ajouter les horaires des ateliers
function artisanoscope_product_content_display_hours(){
  $startTime = get_field("heure_debut");
  $endTime = get_field("heure_fin");
  echo("<p class='artisan-workshops-card-info'>".$startTime." - ".$endTime."</p>");
}
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_display_hours',10);

// 7 - ACTION: Fermer la div avec la classe custom (pour le style au survol)
function artisanoscope_end_card_div() {
  echo('</div>');
}
add_action( 'woocommerce_after_shop_loop_item', 'artisanoscope_end_card_div', 20 );


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