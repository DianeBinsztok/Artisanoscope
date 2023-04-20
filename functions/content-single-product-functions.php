<?php
// CONTENT-SINGLE-PRODUCT.PHP => template de la vue détaillée du produit

// LES SVG
$svg = [
	"date"=> '<svg class="artisanoscope-single-product-info-svg" width="23" height="25" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M6 9H4V11H6V9ZM10 9H8V11H10V9ZM14 9H12V11H14V9ZM16 2H15V0H13V2H5V0H3V2H2C0.89 2 0.00999999 2.9 0.00999999 4L0 18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20H16C17.1 20 18 19.1 18 18V4C18 2.9 17.1 2 16 2ZM16 18H2V7H16V18Z" fill="currentColor"/>
	</svg>',
	"hours"=>'<svg class="artisanoscope-single-product-info-svg" width="25" height="25" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M7 8L10 10V5M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>',
	"location"=>'<svg class="artisanoscope-single-product-info-svg" width="19" height="25" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M7 9.5C6.33696 9.5 5.70107 9.23661 5.23223 8.76777C4.76339 8.29893 4.5 7.66304 4.5 7C4.5 6.33696 4.76339 5.70107 5.23223 5.23223C5.70107 4.76339 6.33696 4.5 7 4.5C7.66304 4.5 8.29893 4.76339 8.76777 5.23223C9.23661 5.70107 9.5 6.33696 9.5 7C9.5 7.3283 9.43534 7.65339 9.3097 7.95671C9.18406 8.26002 8.99991 8.53562 8.76777 8.76777C8.53562 8.99991 8.26002 9.18406 7.95671 9.3097C7.65339 9.43534 7.3283 9.5 7 9.5ZM7 0C5.14348 0 3.36301 0.737498 2.05025 2.05025C0.737498 3.36301 0 5.14348 0 7C0 12.25 7 20 7 20C7 20 14 12.25 14 7C14 5.14348 13.2625 3.36301 11.9497 2.05025C10.637 0.737498 8.85652 0 7 0Z" fill="currentColor"/>
	</svg>',
	"people"=>'<svg class="artisanoscope-single-product-info-svg" width="21" height="21" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M8 8C6.9 8 5.95833 7.60833 5.175 6.825C4.39167 6.04167 4 5.1 4 4C4 2.9 4.39167 1.95833 5.175 1.175C5.95833 0.391667 6.9 0 8 0C9.1 0 10.0417 0.391667 10.825 1.175C11.6083 1.95833 12 2.9 12 4C12 5.1 11.6083 6.04167 10.825 6.825C10.0417 7.60833 9.1 8 8 8ZM0 16V13.2C0 12.6333 0.146 12.1123 0.438 11.637C0.729334 11.1623 1.11667 10.8 1.6 10.55C2.63333 10.0333 3.68333 9.64567 4.75 9.387C5.81667 9.129 6.9 9 8 9C9.1 9 10.1833 9.129 11.25 9.387C12.3167 9.64567 13.3667 10.0333 14.4 10.55C14.8833 10.8 15.2707 11.1623 15.562 11.637C15.854 12.1123 16 12.6333 16 13.2V16H0Z" fill="currentColor"/>
	</svg>',
	"kid_friendly"=>'<svg class="artisanoscope-single-product-info-svg" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M9 6C9.53043 6 10.0391 5.78929 10.4142 5.41421C10.7893 5.03914 11 4.53043 11 4C11 3.62 10.9 3.27 10.71 2.97L9 0L7.29 2.97C7.1 3.27 7 3.62 7 4C7 5.1 7.9 6 9 6ZM15 9H10V7H8V9H3C1.34 9 0 10.34 0 12V21C0 21.55 0.45 22 1 22H17C17.55 22 18 21.55 18 21V12C18 10.34 16.66 9 15 9ZM16 20H2V17C2.9 17 3.76 16.63 4.4 16L5.5 14.92L6.56 16C7.87 17.3 10.15 17.29 11.45 16L12.53 14.92L13.6 16C14.24 16.63 15.1 17 16 17V20ZM16 15.5C15.5 15.5 15 15.3 14.65 14.93L12.5 12.8L10.38 14.93C9.64 15.67 8.35 15.67 7.61 14.93L5.5 12.8L3.34 14.93C3 15.29 2.5 15.5 2 15.5V12C2 11.45 2.45 11 3 11H15C15.55 11 16 11.45 16 12V15.5Z" fill="currentColor"/>
	</svg>',
	"beginners_accepted"=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g id="feBeginner0" fill="none" fill-rule="evenodd" stroke="none" stroke-width="1"><g id="feBeginner1" fill="currentColor" fill-rule="nonzero"><path id="feBeginner2" d="M12 7.529L5 5.09v10.372l7 3.251V7.529ZM5.632 3.108L12 5.326l6.368-2.218c1.047-.365 2.18.227 2.53 1.322c.067.213.102.436.102.66v10.372c0 .826-.465 1.574-1.188 1.91L12 21l-7.812-3.628C3.465 17.036 3 16.288 3 15.462V5.09C3 3.936 3.895 3 5 3c.215 0 .429.037.632.108Z"/></g></g></svg>',
	"price"=>'<svg class="artisanoscope-single-product-info-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15 18.5A6.48 6.48 0 0 1 9.24 15H14c.55 0 1-.45 1-1s-.45-1-1-1H8.58c-.05-.33-.08-.66-.08-1s.03-.67.08-1H14c.55 0 1-.45 1-1s-.45-1-1-1H9.24A6.491 6.491 0 0 1 15 5.5c1.25 0 2.42.36 3.42.97c.5.31 1.15.26 1.57-.16c.58-.58.45-1.53-.25-1.96A9.034 9.034 0 0 0 15 3c-3.92 0-7.24 2.51-8.48 6H4c-.55 0-1 .45-1 1s.45 1 1 1h2.06a8.262 8.262 0 0 0 0 2H4c-.55 0-1 .45-1 1s.45 1 1 1h2.52c1.24 3.49 4.56 6 8.48 6c1.74 0 3.36-.49 4.74-1.35c.69-.43.82-1.39.24-1.97c-.42-.42-1.07-.47-1.57-.15c-.99.62-2.15.97-3.41.97z"/></svg>'
];


// FONCTIONS CUSTOM
// 1 - Récupérez les valeurs et afficher les champs ACF pour ce produit
function artisanoscope_display_acf_fields() {
	global $product;
	global $svg;
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
	if(!empty($artisan)){
		echo "<a href=".$artisanUrl." class='artisanoscope-single-product-info-artisan-link'>";
		echo "<div class='artisanoscope-single-product-info-artisan-title artisanoscope-single-product-info-acf-field-line'>";
		echo "<img class='artisanoscope-single-product-info-acf-fields-img' src=". $artisanPortraitMini->guid." alt=''/><h3 class='artisanoscope-single-product-info-artisan-hover-name'>Avec ". $artisanName."</h3>";
		echo "</div>";
		echo "<span class='artisanoscope-single-product-info-artisan-hover'><img class='artisanoscope-single-product-info-artisan-hover-img' src=". $artisanPortrait->guid." alt=''/><h3 class='artisanoscope-single-product-info-artisan-hover-name'>".$artisanName."</h3><p class='artisanoscope-single-product-info-artisan-description'>".$artisanIntroduction."</p></span>";
		echo "</a>";
	}
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["hours"]."<p> Durée: 3H</p></div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["kid_friendly"]."<p> Adapté aux enfant</p></div>";
	echo "</section>";
}
add_action( 'woocommerce_single_product_summary', 'artisanoscope_display_acf_fields', 10);
 // 2 - Ajouter les prix des variations dans les champs d'options
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

			$parent = wp_get_post_parent_id( $variation_id[0]);
			if ( $parent > 0 ) {
				$_product = new WC_Product_Variation( $variation_id[0] );
				/*if($term == "adulte" || $term == "enfant"){*/
					return $term . ' (' . wp_kses( wc_price( $_product->get_price() ), array() ) . ')';
				/*}*/
			}
			return $term;
}
 // 3 - Si le produit est variable, effacer les prix en en-tête et les ajouter dans les champs d'options (appelle la fonction précédente)
function artisanoscope_display_options_prices_if_variations(){
	global $product;
	//Si le produit est variable
		if($product->get_attributes()){
		//Si le produit a un attribut "participants" avec  des options

			if(isset($product->get_attributes()["participants"]) && isset($product->get_attributes()["participants"]["options"])){
				//retirer la mise en page normale du prix
				remove_action('woocommerce_single_product_summary','woocommerce_template_single_price');
				//ajouter les prix dans les champs d'option avec la fonction précédente
				add_filter( 'woocommerce_variation_option_name', 'artisanoscope_display_price_in_variation_option_name' );
			}
		}
}
add_action('woocommerce_single_product_summary', 'artisanoscope_display_options_prices_if_variations', 9);

// 4 - Ajouter une consigne en cas d'achat de ticket pour enfants
function artisanoscope_display_warning_for_children_ticket(){
	global $product;

	// 1 - Si le produit a des attributs
	if($product->get_attributes()){
		// 2 - S'il a un attribut "participants" avec une option "enfant"
			if(isset($product->get_attributes()["participants"]["options"])&&in_array("enfant", $product->get_attributes()["participants"]["options"])){
				//3 - Ajouter l'encadré d'avertissement
				echo("<p class='atelier-option-warning hide'>
				Notez que les enfants doivent être accompagnés d'un moins un adulte
				</p>");
				wp_enqueue_script("childrenMessage", get_stylesheet_directory_uri().'/assets/js/artisanoscopeChildrenTicketsMessage.js');
				//wp_enqueue_script("artisanoscopeDisplayWarningForChildrenTicket", get_stylesheet_directory_uri().'/assets/js/custom-scripts.js');
				//wp_add_inline_script( 'custom-scripts', 'artisanoscopeDisplayWarningForChildrenTicket');
			}
	}
}
add_action( 'woocommerce_before_add_to_cart_quantity', 'artisanoscope_display_warning_for_children_ticket' );



// 5 - Affichage des dates disponibles et infos associées
// Afficher l'attribut "Dates" sous forme de boutons
function artisanoscope_display_date_options($html, $args) {
	global $product;
	global $svg;
    // Vérifier que l'attribut est "Dates"
    if ($args['attribute'] === 'Date') {
		$dates ='';
		$variations = $product->get_available_variations();
		foreach($variations as $variation){

			$dates .= '
			<div class="artisanoscope-date-option" name="date">
				<div class="artisanoscope-date-option-line">
				'.$svg["date"].'
				<h3 class="artisanoscope-date-option-date">'.$variation["attributes"] ["attribute_date"].'</h3>
				</div>

				<div class="artisanoscope-date-option-line">
				'.$svg["hours"].'
					<p class="artisanoscope-date-option-hours">De '.$variation["start_hour"].' à '.$variation["end_hour"].'</p>
				</div>
				<div class="artisanoscope-date-option-line">
				'.$svg["location"].'
					<p class="artisanoscope-date-option-location">'.$variation["location"].'</p>
				</div>
				<div class="artisanoscope-date-option-line">
				'.$svg["people"].'
					<p class="artisanoscope-date-option-availabilities"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$variation["availabilities"].'places </span> disponibles</p>
				</div>
				<div class="artisanoscope-date-option-line">
				'.$svg["price"].'
					<p class="artisanoscope-date-option-price"><p class="artisanoscope-date-option-price">'.$variation["price"].'</p>
				</div>
			</div>';
		}

		
		$html= '
		<div id="date" class="artisanoscope-date-options-container" name="attribute_date" data-attribute_name="attribute_date">
		'.$dates.'
    	</div>
		<input type="hidden" name="start_hour" value="'.$variation["start_hour"].'">
		<input type="hidden" name="end_hour" value="'.$variation["end_hour"].' ">
		<input type="hidden" name="location" value="'.$variation["location"].'">
		';
		
    }
	$html.='
	<script>
	document.querySelectorAll(".artisanoscope-date-option").forEach(selection=>{
		selection.addEventListener("click", event=>{
			event.preventDefault();
			value = document.getElementsByName("variation_id")[0];
			value.setAttribute("value", "'.$variation["variation_id"].'");
			console.log(value);
			document.querySelector(".single_add_to_cart_button").classList.remove("disabled");
			document.querySelector(".single_add_to_cart_button").classList.remove("wc-variation-selection-needed");
		})
	}); 
	</script>';
    return $html;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_date_options', 10, 2);

// 6 - Enlever le fil d'Ariane
remove_action("woocommerce_before_main_content", "woocommerce_breadcrumb", 20);

// 7 - Enlever les méta: SKU, catégories et tags
function artisanoscope_remove_sku( $enabled ) {
	// Si on est pas dans l'admin et si on est sur la page produit
    if ( !is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'artisanoscope_remove_sku' );
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_meta", 40);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_sharing", 50);

//TODO 6 - Dans la fiche produit, les "Produits similaires" doivent afficher des produits de même catégorie et visibles au catalogue

/* Ajouter des champs custom aux variations de produits */

// 1. Ajout des champs custom à l'input @ Product Data > Variations > Single Variation
add_action( 'woocommerce_variation_options_pricing', 'artisanoscope_add_custom_field_to_variations', 10, 10 );
function artisanoscope_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
    //https://woocommerce.wp-a2z.org/oik_api/woocommerce_wp_text_input/
    //https://pluginrepublic.com/woocommerce-custom-fields/

    //Heure de début
    woocommerce_wp_text_input( array(
        'id' => 'start_hour[' . $loop . ']',
        'class' => 'short',
        'type'  => 'time',
        'label' => __( 'Heure de début', 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'start_hour', true )
    ) );

    //Heure de fin
    woocommerce_wp_text_input( array(
        'id' => 'end_hour[' . $loop . ']',
        'class' => 'short',
        'type'  => 'time',
        'label' => __( 'Heure de fin', 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'end_hour', true )
    ) );

    //Lieu
    woocommerce_wp_radio(array(
        'id' => 'location[' . $loop . ']',
        'class' => 'short',
        'type'  => 'radio',
        'options'     => array(
            'Hostun1'    => __("La Baume d'Hostun, salle 1", 'woocommerce' ),
            'Hostun2' => __("La Baume d'Hostun, salle 2", 'woocommerce' ),
            'Autre' => __('Autre lieu:', 'woocommerce' ),
        ),
        'label' => __( 'Lieu', 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'location', true )
    ));

    //Autre lieu (si l'atelier n'est pas à la Baume d'Hostun)
    woocommerce_wp_textarea_input( array(
        'id' => 'other_location[' . $loop . ']',
        'class' => 'short',
        'type'  => 'textarea',
        'label' => __( "Si l'atelier ne se tient pas à la Baume d'Hostun, renseignez une adresse:", 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'other_location', true )
    ) );
}
 
// -----------------------------------------
// 2. Enregistrer le champs custom en même temps que la variation du produit
add_action( 'woocommerce_save_product_variation', 'artisanoscope_save_custom_field_variations', 10, 2 );
function artisanoscope_save_custom_field_variations( $variation_id, $i ) {
    //Heure de début
    $start_hour_field = $_POST['start_hour'][$i];
    if ( isset( $start_hour_field ) ) update_post_meta( $variation_id, 'start_hour', esc_attr( $start_hour_field ) );

    //Heure de fin
    $end_hour_field = $_POST['end_hour'][$i];
    if ( isset( $end_hour_field ) ) update_post_meta( $variation_id, 'end_hour', esc_attr( $end_hour_field ) );

    //Lieu
    $location_field = $_POST['location'][$i];
    if ( isset( $location_field ) ) update_post_meta( $variation_id, 'location', esc_attr( $location_field ) );

    $other_location_field = $_POST['other_location'][$i];
    if ( isset( $other_location_field ) ) update_post_meta( $variation_id, 'other_location', esc_attr( $other_location_field ) );
}
 
// -----------------------------------------
// 3. Enregistrer le champs custom dans les données de variation de produit
add_filter( 'woocommerce_available_variation', 'artisanoscope_add_custom_field_variation_data' );
function artisanoscope_add_custom_field_variation_data( $variations ) {
	// C'est ce qui s'affichera en front office, quand on sélectionne une date:
	global $svg;
	global $product;

	//Horaires

	$variations['hours'] = $svg["hours"].get_post_meta( $variations[ 'variation_id' ], 'start_hour', true ).' - '.get_post_meta( $variations[ 'variation_id' ], 'end_hour', true );

	$variations['start_hour'] = get_post_meta( $variations[ 'variation_id' ], 'start_hour', true );
	$variations['end_hour'] = get_post_meta( $variations[ 'variation_id' ], 'end_hour', true );

	//Lieu
	$location = "";
	$location_field=get_post_meta( $variations[ 'variation_id' ], 'location', true );
	if($location_field==="Autre"){
		$location = get_post_meta( $variations[ 'variation_id' ], 'other_location', true );
	}else{
		$location = get_post_meta( $variations[ 'variation_id' ], 'location', true );
	}
	$variations['location'] = $location;

	// Les places disponibles (stock) pour chaque variations
	// 1 - Je récupère la variable Woocommerce $variations["availability_html"]
	$default_availability_tag = $variations["availability_html"];
	// 2 - je retire ses balises et styles par défaut
	$availability = str_replace('<p class="stock in-stock">','',
	$default_availability_tag);
	$availability = str_replace(' en stock</p>','',$availability);
	// 3 -  Je ne garde que le nombre
	$variations["availabilities"] = $availability;

	// Pareil pour le prix:
	// 1 - Je récupère la variable Woocommerce $variations["price_html"]
	$default_price_tag = $variations["price_html"];
	// 2 - je retire ses balises et styles par défaut
	$price = str_replace('<span class="price"><span class="woocommerce-Price-amount amount"><bdi>','',
	$default_price_tag);
	$price = str_replace('</span></span><span class="woocommerce-Price-currencySymbol">€</span></bdi>','',$price);
	// 3 -  Je ne garde que le nombre
	$variations["price"] = $price;

	return $variations;
}


/*TRASH*/


function artisanoscope_display_acf_fieldsx() {
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
		<br/>301 Côte Simond<br/>26730 La Baume-d'Hostun</p>";
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
	global $svg;
	
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

	// Si la date est saisie comme variation mais qu'il n'y en a qu'une: l'afficher dans l'encadré
	if(!array_key_exists("date", $product->get_attributes()) || (array_key_exists("date", $product->get_attributes()) && count($product->get_attributes()["date"]["options"]) == 1)){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["date"]. "<p>  Le ". $date . "</p></div>";
	}

	// ancienne version: affichage du champs ACF "Date"
	//echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$dateSVG. "<p>  Le ". $date . "</p></div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["hours"]."<p>  De " . $startTime . " à " . $endTime . "</p></div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["location"].$address."</div>";
	echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["people"]."<p> Il reste <span class='artisanoscope-single-product-info-availabilities'>" . $availabilities . " places </span>disponibles pour cet atelier</p></div>";

	// Le champs Âge minimum n'est pas requis. N'afficher cette partie du template que s'il est renseigné
	if(!empty($minAge)){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["kid_friendly"]."<p> Âge minimum: ". $minAge . "</p></div>";
	}

	echo "</section>";
}
//add_action( 'woocommerce_single_product_summary', 'artisanoscope_display_acf_fieldsx', 10);

// 5 - Affichage des dates en tant qu'attributs de produit
function artisanoscope_display_date_optionsx($html, $args) {
	global $product;
    // Vérifier que l'attribut est "Dates"
    if ($args['attribute'] === 'Date') {
        // Remplacer la liste déroulante par des boutons
		$html="";

		$html .='<div class="artisanoscope-date-options-container">';

		foreach($product->get_attributes()["date"]["options"] as $option){
			/**/
			$html .= '<div class="artisanoscope-date-option-container">';
			$html .='<input type="radio" class name="attribute_date" data-attribute_name="attribute_date" value="'.$option.'" class="attached enabled"><label for="attribute_date">'.$option.'</label>';
			$html .='</div>';
			
			/* 
			$html .= '<div class="artisanoscope-date-option">';
			$html .= '<input type="button" class name="attribute_date" data-attribute_name="attribute_date" value="'.$option.'" class="attached enabled">';
			$html .='</div>';
			*/
		}
		$html .='</div>';
		$html .= '<style>
			/*input[type="radio"] { opacity:0; }*/
			.artisanoscope-date-options-container {
				display:flex;
			  }

			.artisanoscope-date-option{
				margin:0.3em;
				cursor:pointer;
			}

			.artisanoscope-date-option input[type="button"]:checked {
				background-color:pink;
			}
			</style>';
    }
    return $html;
}
//add_filter('woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_date_optionsx', 10, 2);


function artisanoscope_display_workshop_datesx($html,$args){
	$output = '';
    $options = $args['options'];
    $attribute_name = $args['attribute'];
	//var_dump($attribute_name);
	//var_dump($html);
    if ( $attribute_name == 'Date' ) {
        foreach ( $options as $option ) {
			//echo("<button>".$option."</button>");
			$html .="<button>".$option."</button>";
			
            $checked = selected( sanitize_title( $args['selected'] ), sanitize_title( $option ), false );
            $output .= '<label><input type="button" name="' . esc_attr( 'attribute_' . sanitize_title( $attribute_name ) ) . '" value="' . esc_attr( sanitize_title( $option ) ) . '"' . $checked . ' />' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>';
			
        }
    }

    return $html;
}
//add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_workshop_datesx', 10, 2 );

function artisanoscope_display_workshop_dates(){
	global $product;

	// 1 - Si le produit a des attributs
	if($product->get_attributes()){
		// 2 - S'il a un attribut "participants" avec une option "enfant"
			if($product->get_attributes()["date"]["options"]){


				$date_options = $product->get_attributes()["date"]["options"];
				foreach($date_options as $option){
					echo("<button>".$option."</button>");
				}
			}
	}
}
//add_action( 'woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_workshop_dates', 10, 2 );
//add_action( 'woocommerce_before_add_to_cart_quantity', 'artisanoscope_display_workshop_dates' );