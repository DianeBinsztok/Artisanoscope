<?php
// CONTENT-SINGLE-PRODUCT.PHP => template de la vue détaillée du produit

// FONCTIONS CUSTOM
// 1 - Afficher les infos d'artisan si ce champs a été renseigné et appeler le reste du template en fonction du type de produit
add_action( 'woocommerce_single_product_summary', 'artisanoscope_display_acf_fields_and_check_for_variations', 10);
function artisanoscope_display_acf_fields_and_check_for_variations() {
	global $product;
    global $svg;
	$type = $product->get_type();
	$format = get_field("prod_format");

    //Infos globales pour ateliers et formations

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
	if(isset($artisan)){
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
	// I - Artisan
	if(isset($artisan)&&!empty($artisan)){
		echo("<section class='artisanoscope-product-info-container'>");

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
	if(!empty(get_field("prod_age"))&&in_array("enfant", get_field("prod_age"))){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["kid_friendly"]."<p> Adapté aux enfants</p></div>";
	}
	if(get_field("prod_debutant")&&!empty(get_field("prod_debutant"))){
		echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["beginners_accepted"]."<p> Accessible aux débutants</p></div>";
	}

	// III - Description du produit:
	echo "<div class='artisanoscope-single-product-info-description'>";
	wc_get_template( 'single-product/short-description.php' );
	echo "</div>";

	// VI - Infos complémentaires: champs selon le type de produit
	// 1 - Si atelier simple
	if($format === "ponctuel" &&$type === "simple"){
		if(isset($date)&&!empty($date)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["date"]."<p>Le ".$date."</p></div>";
		}
		if(isset($start_hour)&&!empty($start_hour)&&isset($end_hour)&&!empty($end_hour)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["hours"]."<p> De ".$start_hour." à ".$end_hour."</p></div>";	
		}
		if(isset($location)&&!empty($location)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["location"]."<p>".$location."</p></div>";	
		}
	}
	// 2 - Si formation simple
	if($format === "abonnement" &&$type === "simple"){
		if(isset($periodicite)&&!empty($periodicite)&&isset($start_hour)&&!empty($start_hour)&&isset($end_hour)&&!empty($end_hour)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["recurrence"]."<p>".$periodicite.", de ".$start_hour." à ".$end_hour."</p></div>";
		}
		if(isset($start_date)&&!empty($start_date)&&isset($end_date)&&!empty($end_date)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["date"]."<p> Du ".$start_date." au ".$end_date." </p></div>";
		}
		if(isset($location)&&!empty($location)){
			echo "<div class='artisanoscope-single-product-info-acf-field-line'>".$svg["location"]."<p>".$location."</p></div>";	
		}
	}

	// 3 - Si atelier ou formation variable 
    if($type==="variable" && $format === "ponctuel"){
		echo("</div><div>");
		add_filter('woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_display_workshop_options', 10, 3);
    }
}

function artisanoscope_replace_stock_string($html, $product) {

	$availability = $product->get_stock_quantity();
	if($availability>=4){
		$html = '<p class="artisanoscope-product-option-availabilities plenty"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$availability.' places </span> disponibles</p>';
	}elseif($availability<4&&$availability>=2){
		$html = '<p class="artisanoscope-product-option-availabilities scarce"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$availability.' places </span> disponibles</p>';
	}elseif($availability===1){
		$html = '<p class="artisanoscope-product-option-availabilities scarce">Il reste<span class="stock in-stock artisanoscope-single-product-info-availabilities"> une place</span> disponible</p>';
	}else{
		$html = "";
	}
	return $html;
}
add_filter( 'woocommerce_get_stock_html', 'artisanoscope_replace_stock_string', 10, 3 );

function artisanoscope_display_workshop_options(){
    global $product;
	global $svg;
	$format = get_field("prod_format");
	$variations = $product->get_available_variations();
		if (isset($variations)) {
			$options ='';
			
			foreach($variations as $variation){
				// N'afficher une variation que si le prix et le stock sont renseignés
				if(/*isset($variation["price"])&&!empty($variation["price"])&&*/isset($variation["availabilities"])&&!empty($variation["availabilities"])){
					$options .= '<a href="#" class="artisanoscope-product-option" name="option" id="'.$variation["variation_id"].'">';

					
					// Pour les formations: si aucune date n'est renseignée, la date 01/01/1970 s'affiche automatiquement => ne pas afficher de variations de date pour les formations
					
					if(isset($variation["date"])&& !empty($variation["date"])){
						$options .= '<div class="artisanoscope-product-option-line option-title">
						'.$svg["date"].'
						<h3 class="artisanoscope-product-option-title artisanoscope-product-option-line">'.$variation["date"].'</h3>
						</div>';
					}
					if(isset($variation["start_hour"])&&isset($variation["end_hour"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.$svg["hours"].'
						<p class="artisanoscope-product-option-hours">De '.$variation["start_hour"].' à '.$variation["end_hour"].'</p>
						</div>';
					}
					if(isset($variation["location"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.$svg["location"].'
						<p class="artisanoscope-product-option-location">'.break_line_on_comma($variation["location"]).'</p>
						</div>';
					}
					if(isset($variation["availabilities"])){
						$options .= '<div class="artisanoscope-product-option-line">
						'.$svg["people"].'
							<p class="artisanoscope-product-option-availabilities"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$variation["availabilities"].'places </span> disponibles</p>
						</div>';
					}
					if(isset($variation["price"])&&!empty($variation["price"])){
						add_action( 'woocommerce_before_add_to_cart_button', 'artisanoscope_display_selected_variation_price');
						$price = $variation["price"];
						$options .= '<div class="artisanoscope-product-option-line">
						'.$svg["price"].'
						<p class="artisanoscope-product-option-price">'.$price.'</p>
						</div>';

					}
					$options .= '</a>';
				}
			}
			
			$html= '
			<section id="course-options" class="artisanoscope-product-options-container" name="course-attribute" data-attribute_name="course-attribute">
			'.$options.'
			</section>
			';
			
		}
		wp_enqueue_script("chooseDateOptionAndAddToCart", get_stylesheet_directory_uri().'/assets/js/artisanoscopeSingleProductScripts.js');
		return $html;
}

function artisanoscope_display_selected_variation_price(){
    // Afficher le prix de la variation avant le bouton d'ajout au panier
    echo '<div id="artisanoscope-selected-variation-price" class="woocommerce-Price-amount" style="font-weight:500; font-size: 1.5em; color:green;"></div>';
}

function break_line_on_comma($string){
	return str_replace(',', '<br/>', $string);
}

// Enlever le fil d'Ariane
remove_action("woocommerce_before_main_content", "woocommerce_breadcrumb", 20);

// Enlever les méta: SKU, catégories et tags
function artisanoscope_remove_sku( $enabled ) {
	// Si on est pas dans l'admin et si on est sur la page produit
    if ( !is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'artisanoscope_remove_sku' );
//Enlever la descrition
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_excerpt", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_meta", 40);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_sharing", 50);

//infos additionnelles
remove_action("woocommerce_after_single_product_summary", "woocommerce_output_product_data_tabs", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_meta", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_sharing", 20);
remove_action("woocommerce_single_product_summary", "woocommerce_template_single_rating", 20);



