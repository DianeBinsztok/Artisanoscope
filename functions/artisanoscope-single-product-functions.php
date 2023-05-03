<?php
// CONTENT-SINGLE-PRODUCT.PHP => template de la vue détaillée du produit
// https://www.businessbloomer.com/woocommerce-visual-hook-guide-single-product-page/

// LES SVG
/*
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
	"beginners_accepted"=>'<svg class="artisanoscope-single-product-info-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g id="feBeginner0" fill="none" fill-rule="evenodd" stroke="none" stroke-width="1"><g id="feBeginner1" fill="currentColor" fill-rule="nonzero"><path id="feBeginner2" d="M12 7.529L5 5.09v10.372l7 3.251V7.529ZM5.632 3.108L12 5.326l6.368-2.218c1.047-.365 2.18.227 2.53 1.322c.067.213.102.436.102.66v10.372c0 .826-.465 1.574-1.188 1.91L12 21l-7.812-3.628C3.465 17.036 3 16.288 3 15.462V5.09C3 3.936 3.895 3 5 3c.215 0 .429.037.632.108Z"/></g></g></svg>',
	"price"=>'<svg class="artisanoscope-single-product-info-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15 18.5A6.48 6.48 0 0 1 9.24 15H14c.55 0 1-.45 1-1s-.45-1-1-1H8.58c-.05-.33-.08-.66-.08-1s.03-.67.08-1H14c.55 0 1-.45 1-1s-.45-1-1-1H9.24A6.491 6.491 0 0 1 15 5.5c1.25 0 2.42.36 3.42.97c.5.31 1.15.26 1.57-.16c.58-.58.45-1.53-.25-1.96A9.034 9.034 0 0 0 15 3c-3.92 0-7.24 2.51-8.48 6H4c-.55 0-1 .45-1 1s.45 1 1 1h2.06a8.262 8.262 0 0 0 0 2H4c-.55 0-1 .45-1 1s.45 1 1 1h2.52c1.24 3.49 4.56 6 8.48 6c1.74 0 3.36-.49 4.74-1.35c.69-.43.82-1.39.24-1.97c-.42-.42-1.07-.47-1.57-.15c-.99.62-2.15.97-3.41.97z"/></svg>'
];
*/

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



