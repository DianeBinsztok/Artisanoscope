<?php

// I - AFFICHAGE DES CHAMPS PERSONNALISES
// Afficher les infos d'artisan si ce champs a été renseigné et appeler le reste du template en fonction du type de produit
function artisanoscope_display_acf_fields_and_check_for_variations() {
	global $product;

	/*TESTS DATES*/
	$date_string = "06/06/2023";
	//Le format de date
	$date_format1 = DateTime::createFromFormat('d/m/Y', $date_string, new DateTimeZone('Europe/Paris'));
	$date_format2 = date("d/m/Y",strtotime($date_string));
	$date = get_field("prod_date");
	$tomorrow = date("d/m/Y",strtotime('tomorrow'));
	/*
	if ($date == $tomorrow ) {
		echo($date .'=='. $tomorrow);
	}
	*/
	/*
	$date = $date_format->getTimestamp();
	echo("date, sans timezone =>");
	var_dump($date);
	var_dump(strtotime('tomorrow 00:00:00'));
	*/
	/*FIN - TESTS DATES*/

	$type = $product->get_type();
	$format = get_field("prod_format");
    //Infos globales pour ateliers et formations

	//Description longue (remplacera la description courte -summary- dans le template)
	$description = $product->get_description();

    //Atelier
	$date = get_field("prod_date");

	//Atelier & Formation
	$start_hour = get_field("prod_heure_debut");
	$end_hour = get_field("prod_heure_fin");

    //Formation
	$start_date = get_field("prod_date_debut");
	$end_date = get_field("prod_date_fin");
	$periodicite= get_field("prod_periodicite");
	$global_duration_raw = get_field("prod_duree_formation");
	// Si la saisie a une majuscule en première lettre
	$global_duration = lcfirst($global_duration_raw);

    //Lieu
    $location_field = get_field("prod_lieu");
	$location = "";
	if(isset($location_field)&&!empty($location_field)){
		if($location_field=="hostun"){
			$location = "La Baume d'Hostun";
		}elseif($location_field=="autre"){
			$other_location = get_field("prod_autre_lieu");
			if(isset($other_location)&&!empty($other_location)){
				$location = break_line_on_comma($other_location);
			}
		}
	}

	//Infos relatives à l'artisan - si le champs a été renseigné
	$artisan = get_field("prod_artisan");
	//var_dump($artisan);
	if(isset($artisan)&&!empty($artisan)){
		$artisanName = $artisan->art_nom;
		$artisanUrl = $artisan->guid;

		$artisanPortraitID = $artisan->art_portrait;
		$artisanPortrait = get_post($artisanPortraitID);

		$artisanPortraitMiniID = $artisan->art_portrait_mini;
		// Si l'artisan a un portrait mais pas de miniature, on utilisera le portrait
		if(isset($artisanPortraitMiniID)){
			$artisanPortraitMini = get_post($artisanPortraitMiniID);
		}else{
			$artisanPortraitMini = $artisanPortrait;
		}

		$artisanIntroduction = $artisan ->art_introduction;
	}
    // Affichage des champs:
	echo("<section class='artisanoscope-product-info-container'>");
	// I - Artisan
	if(isset($artisan)&&!empty($artisan)){
		if(isset($artisanUrl)&&!empty($artisanUrl)){
			echo "<a href=".$artisanUrl." class='artisanoscope-single-product-info-artisan-link'>";
		}
		echo "<div class='artisanoscope-single-product-info-artisan-title artisanoscope-single-product-info-acf-field-line'>";
		if(isset($artisanPortraitMini)&&!empty($artisanPortraitMini)){
			echo "<img class='artisanoscope-single-product-info-acf-fields-img' src=". $artisanPortraitMini->guid." alt=''/>";
		}
		if(isset($artisanName)&&!empty($artisanName)){
			echo "<h3 class='artisanoscope-single-product-info-artisan-hover-name'>Par ". $artisanName."</h3>";
		}
		echo "</div>";
		echo "<span class='artisanoscope-single-product-info-artisan-hover'>";
		if(isset($artisanPortrait)&&!empty($artisanPortrait)){
			
			echo "<img class='artisanoscope-single-product-info-artisan-hover-img' src=". $artisanPortrait->guid." alt=''/>";
		}
		if(isset($artisanName)&&!empty($artisanName)){
			echo"<h3 class='artisanoscope-single-product-info-artisan-hover-name'>".$artisanName."</h3>";
		}
		if(isset($artisanIntroduction)&&!empty($artisanIntroduction)){
			echo"<p class='artisanoscope-single-product-info-artisan-description'>".$artisanIntroduction."</p>";
		}
		echo "</span>";
		if(isset($artisanUrl)&&!empty($artisanUrl)){
			echo "</a>";
		}
	}
	// II - Arguments: enfants et débutants
	echo("<div class=artisanoscope-kids-and-beginners-container>");
	if(!empty(get_field("prod_age"))&&in_array("enfant", get_field("prod_age"))){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("kid_friendly")."<p> Adapté aux enfants</p></div>";
	}
	if(get_field("prod_debutant")&&!empty(get_field("prod_debutant"))){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("beginners_accepted")."<p> Accessible aux débutants</p></div>";
	}
	echo("</div>");

	// III - Description du produit:
	echo "<div class='artisanoscope-single-product-info-acf-field-line artisanoscope-single-product-info-description'>".$description."</div>";


	// IV - Infos complémentaires: champs selon le type de produit
	// 1 - Si atelier simple
	if($format === "ponctuel" &&$type === "simple"){
		echo("<div class=artisanoscope-single-product-info-container>");
		echo("<h3>Infos pratiques</h3>");
		if(isset($date)&&!empty($date)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("date")."<p>Le ".$date."</p></div>";
		}
		if(isset($start_hour)&&!empty($start_hour)&&isset($end_hour)&&!empty($end_hour)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("hours")."<p> De ".$start_hour." à ".$end_hour."</p></div>";	
		}
		if(isset($location)&&!empty($location)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("location")."<p>".$location."</p></div>";	
		}
		echo("</div>");

		// Envoyer les infos pratiques dans des inputs cachés
		add_action('','');
	}
	// 2 - Si formation
	if($format === "abonnement" &&$type === "simple"){
		echo("<div class=artisanoscope-single-product-info-container>");
		echo("<h3>Infos pratiques</h3>");
		if(isset($periodicite)&&!empty($periodicite)&&isset($start_hour)&&!empty($start_hour)&&isset($end_hour)&&!empty($end_hour)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("recurrence")."<p>".$periodicite.", de ".$start_hour." à ".$end_hour."</p></div>";
		}
		if(isset($start_date)&&!empty($start_date)&&isset($end_date)&&!empty($end_date)){
			echo "
			<div class='artisanoscope-single-product-info-acf-field-line'>".svg("date")."<p> Du ".$start_date." au ".$end_date."</p>
				<div class='artisanoscope-formation-periodicite-info-hover-picto>
					<img class='artisanoscope-formation-periodicite-info-hover-picto src='/wp-content/uploads/2023/06/picto-formation-periodicite-info.png'/>
					<span class='artisanoscope-formation-periodicite-info'>Vacances scolaires non comprises</span>
				</div>

			</div>";
		}
		if(isset($location)&&!empty($location)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".svg("location")."<p>".$location."</p></div>";	
		}
		echo("</div>");
	}
	echo("</section>");
	//Afficher le prix sous les infos globales
	echo('<p class="price">'.$product->get_price_html().'</p>');
	//test
	//echo("</div>");


	// 3 - Si atelier ou formation variable 
    if($type==="variable"){
		echo("</div><div>");
		add_filter('woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_workshop_options', 10, 3);
    }
}
add_action( 'woocommerce_single_product_summary', 'artisanoscope_display_acf_fields_and_check_for_variations', 10);

// Pour les produits variables, contrôler les champs de chaque variation et afficher les options sous forme de vignettes cliquables
function artisanoscope_display_workshop_options(){
    global $product;
	$format = get_field("prod_format");
	$variations = $product->get_available_variations();
		if (isset($variations)) {

			$options ='';
			
			foreach($variations as $variation){
				// N'afficher une variation que les champs requis sont renseignés et cohérents
				if(variation_has_all_required_fields($variation)&&variation_has_coherent_fields($variation)){
					$options .= '<a href="#" class="artisanoscope-product-option" name="option" id="'.$variation["variation_id"].'">';

					//Date - titre
					if(isset($variation["date"])&& !empty($variation["date"])&&$variation["date"]!= "01/01/1970"){
						$options .= '<div class="artisanoscope-product-option-line option-title">
						'.svg("date").'
						<h3 class="artisanoscope-product-option-title artisanoscope-product-option-line">'.$variation["date"].'</h3>
						</div>';
					}
					//Horaires
					if(isset($variation["start_hour"])&&isset($variation["end_hour"])&&!empty($variation["start_hour"])&&!empty($variation["end_hour"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.svg("hours").'
						<p>De <span class="artisanoscope-product-option-start-hour">'.$variation["start_hour"].'</span> à <span class="artisanoscope-product-option-end-hour">'.$variation["end_hour"].'</span></p>
						</div>';
					}
					//Lieu
					if(isset($variation["location"])&&!empty($variation["location"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.svg("location").'
						<p class="artisanoscope-product-option-location">'.break_line_on_comma($variation["location"]).'</p>
						</div>';
					}
					//Lieu
					if(isset($variation["availabilities"])&&!empty($variation["availabilities"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.svg("people").'
							<p class="artisanoscope-product-option-availabilities"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$variation["availabilities"].'</span></p>
						</div>';
					}
					//Prix
					if(isset($variation["price"])&&!empty($variation["price"])){
						add_action( 'woocommerce_before_add_to_cart_button', 'artisanoscope_display_selected_variation_price');
						$price = $variation["price"];
						$options .= '<div class="artisanoscope-product-option-line">
						'.svg("price").'
						<p class="artisanoscope-product-option-price">'.$price.'</p>
						</div>';

					}
					$options .= '</a>';
				}else{
					return;
				}
			}
			$html= '
			<section id="course-options" class="artisanoscope-product-options-container" name="course-attribute" data-attribute_name="course-attribute">
			'.$options.'
			</section>
			';
		}
		wp_enqueue_script("chooseDateOptionAndAddToCart", get_stylesheet_directory_uri().'/assets/js/artisanoscopeSingleProductDisplayPriceScript.js');
		return $html;
}

// II - MODIFICATIONS DE MISE EN PAGE
// Remplacer la mention "en stock" par "places disponibles"
function artisanoscope_replace_stock_string($html, $product) {

	$availability = $product->get_stock_quantity();
	if($availability>=4){
		$html = '<p class="artisanoscope-product-option-availabilities plenty"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$availability.' places </span> disponibles</p>';
	}elseif($availability<4&&$availability>=2){
		$html = '<p class="artisanoscope-product-option-availabilities scarce"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$availability.' places </span> disponibles</p>';
	}elseif($availability===1){
		$html = '<p class="artisanoscope-product-option-availabilities scarce">Il ne reste plus qu\'<span class="stock in-stock artisanoscope-single-product-info-availabilities"> une place</span> disponible</p>';
	}else{
		$html = "";
	}
	return $html;
}
add_filter( 'woocommerce_get_stock_html', 'artisanoscope_replace_stock_string', 10, 3 );

// Afficher le prix de la variation avant le bouton d'ajout au panier
function artisanoscope_display_selected_variation_price(){
    //Cette div sera ciblée par le script '/assets/js/artisanoscopeSingleProductScripts.js' pour afficher et modifier le prix en fonction des choix de l'utilisateur
    echo '<div id="artisanoscope-selected-variation-price" class="woocommerce-Price-amount"></div>';
}
// Saut de ligne à chaque virgule (pour l'affichage des adresses si besoin)
function break_line_on_comma($string){
	return str_replace(',', '<br/>', $string);
}
// Changer le titre des produits similaires
add_filter('gettext', 'artisanoscope_change_related_products_title', 10, 3);
add_filter('ngettext', 'artisanoscope_change_related_products_title', 10, 3);
function artisanoscope_change_related_products_title($translated, $text, $domain)
{
     if ($text === 'Related products' && $domain === 'woocommerce') {
         $translated = esc_html__('Ateliers similaires', $domain);
     }
     return $translated;
}

/* III - SUPPRESSIONS */

// Enlever le fil d'Ariane
remove_action("woocommerce_before_main_content", "woocommerce_breadcrumb", 20);

// Enlever les méta: SKU, catégories et tags
add_filter( 'wc_product_sku_enabled', 'artisanoscope_remove_sku' );
function artisanoscope_remove_sku( $enabled ) {
	// Si on est pas dans l'admin et si on est sur la page produit
    if ( !is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}

//Retirer les onglets de description et avis
add_filter( 'woocommerce_product_tabs', 'artisanoscope_remove_product_tabs', 98 );
function artisanoscope_remove_product_tabs( $tabs ) {
    unset( $tabs['reviews'] );
	unset( $tabs['description'] );
    unset( $tabs['additional_information'] );
    return $tabs;
}

//Enlever la descrition
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_excerpt", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_meta", 40);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_sharing", 50);

//Retirer le prix de son emplacement habituel
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
//Enlever les infos additionnelles
remove_action("woocommerce_after_single_product_summary", "woocommerce_output_product_data_tabs", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_meta", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_sharing", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_rating", 20);


/*TEST SHORTCODES DE COMMENTAIRES*/
//I - Shortcode perso: comment laisser des commentaires pour un artisan?
//Dans le shortcode: vérifier si l'artisan a des commentaires autorisés avant l'affichage du template et du formulaire
//Créer un template de formulaire Elementor
//Ajouter le shortcode du formulaire dans la fonction
//Envoyer en POST le commentaire et l'enregistrer dans les commentaires de l'artisan
//add_action( 'woocommerce_after_single_product_summary', 'artisanoscope_custom_reviews' );
function artisanoscope_custom_reviews(){
	$artisan = get_field("prod_artisan");
	var_dump($artisan);
	echo(do_shortcode('[testimonies artisan="Teika"]'));
	echo("<div style='margin-bottom:3rem; align-items:center; text-align:center;'><a href='#' style=' color:white; font-size:1.2em; background-color: #008670; border-radius:5px; padding:0.8rem; align-self:center; text-align:center;'>Je laisse un témoignage</a></div>");
	//echo (comments_template());
}
//II - Shortcode plugin: comment sélectionner la catégorie artisan programmatiquement?
//add_action( 'woocommerce_after_single_product_summary', 'artisanoscope_custom_reviews_plugin' );
function artisanoscope_custom_reviews_plugin(){
	echo(do_shortcode('[testimonial_view id="1"]'));
	echo("<div style='margin-bottom:3rem; align-items:center; text-align:center;'><a href='#' style=' color:white; font-size:1.2em; background-color: #008670; border-radius:5px; padding:0.8rem; align-self:center; text-align:center;'>Je laisse un témoignage</a></div>");
}
/*FIN - TEST SHORTCODES DE COMMENTAIRES*/



