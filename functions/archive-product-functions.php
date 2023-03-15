<?php
// ARCHIVE-PRODUCT.PHP => catalogue des ateliers

// I - FONCTIONS CUSTOM

// 1 - FILTRE: les ateliers ne s'affichent dans le catalogue que si les champs ACF requis sont renseignés
function artisanoscope_only_display_workshops_with_required_fields($visible, $productId){
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
    
    return $visible; // Par défaut, le produit est affiché
}
// 2 - FILTRE: ordonner les ateliers chronologiquement
function artisanoscope_archive_product_order_by_date($q){
	$meta_query = $q->get('meta_query');
	$meta_query[] = array(
		'key' => 'date',
		'value' => date('Y-m-d'),
		'compare' => '>=',
		'type' => 'DATE'
	);
	$q->set('meta_query', $meta_query);
	$q->set('orderby', 'meta_value');
	$q->set('meta_key', 'date');
	$q->set('order', 'ASC');
  }
// 3 - ACTION: Ajouter la date sous la photo de chaque atelier
function artisanoscope_content_product_display_workshop_date(){
  $date = get_field("date");
  echo("<p class='artisan-workshops-card-info date'>".$date."</p>");
}
// 4 - ACTION: Style custom du titre de produit: remplacer les classes Woocommerce par mes classes custom
function artisanoscope_product_content_custom_class_for_title(){
    global $product;
    //Ajouter mon titre avec la classe custom
    echo("<h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>");
}
// 5 - ACTION: Afficher les horaire pour chaque atelier
function artisanoscope_product_content_display_hours(){
  $startTime = get_field("heure_debut");
  $endTime = get_field("heure_fin");
  echo("<p class='artisan-workshops-card-info'>".$startTime." - ".$endTime."</p>");
}
// 6 - ACTION: Dans la fiche produit, les "Produits similaires" doivent afficher des produits de même catégorie!
function artisanoscope_replace_related_products_with_same_category_products(){
  global $post;
  $categories = get_the_terms( $post->ID, 'product_cat' );
}


// II - MODIFICATION DES HOOKS

// FILTRES

// 1 - Afficher uniquement les ateliers avec toutes les infos requises
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);
// 2 - Ordonner les ateliers par date
add_filter( 'woocommerce_product_query', 'artisanoscope_archive_product_order_by_date' );

// ACTIONS

//Enlever le titre avec son style par défaut
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
// Supprimer les notes des ateliers
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating');
// Supprimer le bouton d'ajout au panier
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
//Supprimer les mentions de vente flash
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');

// 3 - Afficher la date d'atelier avant le nom
add_action('woocommerce_before_shop_loop_item_title', 'artisanoscope_content_product_display_workshop_date',10);
// 4 - Ajouter le titre avec la classe et le style custom
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_custom_class_for_title',10);
// 5 - Ajouter les horaires des ateliers
add_action('woocommerce_shop_loop_item_title', 'artisanoscope_product_content_display_hours',10);





