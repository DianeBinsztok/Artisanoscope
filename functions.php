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

// I - CONTENT-PRODUCT.PHP => template de la vignette de produit

// A - Fonctions custom
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
// FILTRE: rechercher les ateliers par artisan
// FILTRE: rechercher les ateliers par catégorie

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
// ACTION: ordonner les ateliers chronologiquement
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
// Dans la fiche produit, les "Produits similaires" doivent afficher des produits de même catégorie!
function artisanoscope_replace_related_products_with_same_category_products(){
  global $post;
  $categories = get_the_terms( $post->ID, 'product_cat' );
}


// B - Modifications des hooks
// FILTRES
// Afficher uniquement les ateliers avec toutes les infos requises
add_filter('woocommerce_product_is_visible', 'artisanoscope_only_display_workshops_with_required_fields', 10, 2);
// Ordonner les ateliers par date
add_filter( 'woocommerce_product_query', 'artisanoscope_archive_product_order_by_date' );

// ACTIONS
//Supprimer les mentions de vente flash
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash');
// Afficher la date d'atelier avant le nom
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


// II - CONTENT-SINGLE-PRODUCT.PHP => template de la vue détaillée du produit

// A - Fonctions custom
// ACTIONS
// Récupérez les valeurs et afficher les champs ACF pour ce produit
function artisanoscope_display_acf_fields() {
	//Infos complémentaires sur l'atelier
	global $product;
	$availabilities = $product->get_stock_quantity();
	$date = get_field("date");
	$startTime = get_field("heure_debut");
	$endTime = get_field("heure_fin");

	// Afficher le nom du lieu
	$addressField = get_field("lieu");
	$address = "<p class='artisanoscope-single-product-info-acf-fields-address'>".$addressField."</p>";
	if($addressField=="Le Chalutier, salle 1"||$addressField=="Le Chalutier, salle 2"){
		$address = "<p class='artisanoscope-single-product-info-acf-fields-address'>
		<a href='/ou-nous-trouver/#adresse-chalutier'>".$addressField."</a>
		<br/>301 Côte Simon<br/>26730 La Baume-d'Hostun</p>";
	}
	
	$minAge= get_field("age_minimum");

	//Infos relatives à l'artisan - si le champs a été renseigné
	$artisan = get_field("artisan");
	if(!empty($artisan)){

		$artisanName = $artisan ->nom;
		$artisanUrl = $artisan ->guid;
		$artisanPortraitID = $artisan->portrait;
		$artisanPortrait = get_post($artisanPortraitID);

		$artisanPortraitMiniID = $artisan->portrait_mini;
		// Si l'artisan a un portrait mais pas de miniature, on utilisera le portrait
		if(!empty($artisanPortraitMiniID)){
			$artisanPortraitMini = get_post($artisanPortraitMiniID);
		}else{
			$artisanPortraitMini = $artisanPortrait;
		}

		$artisanIntroduction = $artisan ->introduction;
	}

	// les svg:
	$dateSVG = '<svg class="artisanoscope-single-product-info-acf-field-svg" width="23" height="25" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M6 9H4V11H6V9ZM10 9H8V11H10V9ZM14 9H12V11H14V9ZM16 2H15V0H13V2H5V0H3V2H2C0.89 2 0.00999999 2.9 0.00999999 4L0 18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20H16C17.1 20 18 19.1 18 18V4C18 2.9 17.1 2 16 2ZM16 18H2V7H16V18Z" fill="black"/>
	</svg>';
	$hoursSVG = '<svg class="artisanoscope-single-product-info-acf-field-svg" width="25" height="25" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M7 8L10 10V5M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
	$addressSVG = '<svg class="artisanoscope-single-product-info-acf-field-svg" width="19" height="25" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M7 9.5C6.33696 9.5 5.70107 9.23661 5.23223 8.76777C4.76339 8.29893 4.5 7.66304 4.5 7C4.5 6.33696 4.76339 5.70107 5.23223 5.23223C5.70107 4.76339 6.33696 4.5 7 4.5C7.66304 4.5 8.29893 4.76339 8.76777 5.23223C9.23661 5.70107 9.5 6.33696 9.5 7C9.5 7.3283 9.43534 7.65339 9.3097 7.95671C9.18406 8.26002 8.99991 8.53562 8.76777 8.76777C8.53562 8.99991 8.26002 9.18406 7.95671 9.3097C7.65339 9.43534 7.3283 9.5 7 9.5ZM7 0C5.14348 0 3.36301 0.737498 2.05025 2.05025C0.737498 3.36301 0 5.14348 0 7C0 12.25 7 20 7 20C7 20 14 12.25 14 7C14 5.14348 13.2625 3.36301 11.9497 2.05025C10.637 0.737498 8.85652 0 7 0Z" fill="black"/>
	</svg>';
	$availabilitiesSVG = '<svg class="artisanoscope-single-product-info-acf-field-svg" width="21" height="21" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M8 8C6.9 8 5.95833 7.60833 5.175 6.825C4.39167 6.04167 4 5.1 4 4C4 2.9 4.39167 1.95833 5.175 1.175C5.95833 0.391667 6.9 0 8 0C9.1 0 10.0417 0.391667 10.825 1.175C11.6083 1.95833 12 2.9 12 4C12 5.1 11.6083 6.04167 10.825 6.825C10.0417 7.60833 9.1 8 8 8ZM0 16V13.2C0 12.6333 0.146 12.1123 0.438 11.637C0.729334 11.1623 1.11667 10.8 1.6 10.55C2.63333 10.0333 3.68333 9.64567 4.75 9.387C5.81667 9.129 6.9 9 8 9C9.1 9 10.1833 9.129 11.25 9.387C12.3167 9.64567 13.3667 10.0333 14.4 10.55C14.8833 10.8 15.2707 11.1623 15.562 11.637C15.854 12.1123 16 12.6333 16 13.2V16H0Z" fill="black"/>
	</svg>';
	$minAgeSVG = '<svg class="artisanoscope-single-product-info-acf-field-svg" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M9 6C9.53043 6 10.0391 5.78929 10.4142 5.41421C10.7893 5.03914 11 4.53043 11 4C11 3.62 10.9 3.27 10.71 2.97L9 0L7.29 2.97C7.1 3.27 7 3.62 7 4C7 5.1 7.9 6 9 6ZM15 9H10V7H8V9H3C1.34 9 0 10.34 0 12V21C0 21.55 0.45 22 1 22H17C17.55 22 18 21.55 18 21V12C18 10.34 16.66 9 15 9ZM16 20H2V17C2.9 17 3.76 16.63 4.4 16L5.5 14.92L6.56 16C7.87 17.3 10.15 17.29 11.45 16L12.53 14.92L13.6 16C14.24 16.63 15.1 17 16 17V20ZM16 15.5C15.5 15.5 15 15.3 14.65 14.93L12.5 12.8L10.38 14.93C9.64 15.67 8.35 15.67 7.61 14.93L5.5 12.8L3.34 14.93C3 15.29 2.5 15.5 2 15.5V12C2 11.45 2.45 11 3 11H15C15.55 11 16 11.45 16 12V15.5Z" fill="black"/>
	</svg>';
	
	// Afficher les champs ACF:
	echo "<section style='margin-bottom:1.5rem;' class='artisanoscope-single-product-info-acf-fields'>";

	// Le champs artisan n'est pas requis. N'afficher cette partie du template que s'il est renseigné
	if(!empty($artisan)){
		echo "<a href=".$artisanUrl." class='artisanoscope-single-product-info-artisan-link'>";
		echo "<div class='artisanoscope-single-product-info-artisan-title artisanoscope-single-product-info-acf-field-line'>";
		echo "<img class='artisanoscope-single-product-info-acf-fields-img' src=". $artisanPortraitMini->guid." alt=''/><h3 class='artisanoscope-single-product-info-artisan-hover-name'>Avec ". $artisanName."</h3>";
		echo "</div>";
		echo "<span class='artisanoscope-single-product-info-artisan-hover'><img class='artisanoscope-single-product-info-artisan-hover-img' src=". $artisanPortrait->guid." alt=''/><h3 class='artisanoscope-single-product-info-artisan-hover-name'>".$artisanName."</h3><p class='artisanoscope-single-product-info-artisan-description'>".$artisanIntroduction."</p></span>";
		echo "</a>";
	}
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$dateSVG. "<p>  Le ". $date . "</p></div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$hoursSVG."<p>  De " . $startTime . " à " . $endTime . "</p></div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$addressSVG.$address."</div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$availabilitiesSVG."<p> Il reste <span class='artisanoscope-single-product-info-availabilities'>" . $availabilities . " places </span>disponibles pour cet atelier</p></div>";

	// Le champs Âge minimum n'est pas requis. N'afficher cette partie du template que s'il est renseigné
	if(!empty($minAge)){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$minAgeSVG."<p> Âge minimum: ". $minAge . "</p></div>";
	}

	echo "</section>";
 }
 // Ajouter les prix des variations dans les champs d'options
 function artisanoscope_display_price_in_variation_option_name( $term ) {
	global $wpdb, $product;

	if ( empty( $term ) ) return $term;
	if ( empty( $product->get_id() ) ) return $term;

	$id = $product->get_id();

	$result = $wpdb->get_col( "SELECT slug FROM {$wpdb->prefix}terms WHERE name = '$term'" );

	$term_slug = ( !empty( $result ) ) ? $result[0] : $term;

	$query = "SELECT postmeta.post_id AS product_id
				FROM {$wpdb->prefix}postmeta AS postmeta
					LEFT JOIN {$wpdb->prefix}posts AS products ON ( products.ID = postmeta.post_id )
				WHERE postmeta.meta_key LIKE 'attribute_%'
					AND postmeta.meta_value = '$term_slug'
					AND products.post_parent = $id";

	$variation_id = $wpdb->get_col( $query );

	$parent = wp_get_post_parent_id( $variation_id[0] );

	if ( $parent > 0 ) {
		 $_product = new WC_Product_Variation( $variation_id[0] );
		 return $term . ' (' . wp_kses( wc_price( $_product->get_price() ), array() ) . ')';
	}
	return $term;

}
 // Si le produit est variable, effacer les prix en en-tête et les ajouter dans les champs d'options (appelle la fonction suivante)
 function artisanoscope_display_options_prices_if_variations(){
	global $product;
	//Si le produit est variable
	if($product->get_attributes()){
		//Si le produit a un attribut "participants" avec  des options
		if($product->get_attributes()["participants"]["options"]){
		// retirer la mise en page normale du prix
			remove_action('woocommerce_single_product_summary','woocommerce_template_single_price');
			add_filter( 'woocommerce_variation_option_name', 'artisanoscope_display_price_in_variation_option_name' );
			/*
				foreach($product->get_attributes()["participants"]["options"] as $option){
					var_dump(wc_price( $product->get_price()));
					echo("<p>Prix par ".$option." ".wc_price( $product->get_price() )."</p>");
				}
			*/
		}
	}
	
 }
// Ajouter une consigne en cas d'achat de ticket pour enfants
function artisanoscope_display_warning_for_children_ticket(){
	global $product;

	// Si le produit a des attributs
	if($product->get_attributes()){
		// s'il a un attribut "participants" avec une option "enfant"
		if(in_array("enfant",$product->get_attributes()["participants"]["options"])){

			//1 - Ajouter l'encadré d'avertissement
			echo("<p class='atelier-option-warning hide' style='color:#BB4461; border:1px solid #BB4461; background-color:#FFECF0; border-radius: 7px; padding:1em;'>Notez que les enfants doivent être accompagnés d'un moins un adulte</p>");

			//2 - Script: détection du choix et affichage de l'encadré
			wc_enqueue_js( '
			$( "input.variation_id" ).change( function(){
				let atelierOptionWarning = document.querySelector(".atelier-option-warning");
				let userChoice = document.getElementById("participants").value;
				//console.log(userChoice);
				if(userChoice == "enfant"){
					atelierOptionWarning.classList.remove("hide");
				}else{
					atelierOptionWarning.classList.add("hide");
				}
			 });	
		');
		}
	}
	
 }

// B - Modifications des hooks 
// Avant d'ajouter un produit au panier
add_action( 'woocommerce_before_add_to_cart_quantity', 'artisanoscope_display_warning_for_children_ticket' );

// Afficher les champs ACF
add_action( 'woocommerce_single_product_summary', 'artisanoscope_display_acf_fields', 6);

// Si les prix sont variables: effacer le prix en haut du résumé et les afficher dans les options 
add_action('woocommerce_single_product_summary', 'artisanoscope_display_options_prices_if_variations', 9);

//Supprimer la ligne de meta?
//remove_action('woocommerce_single_product_summary', 'WC_Structured_Data::generate_product_data()');