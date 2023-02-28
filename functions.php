<?php
/**
**activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // styles custom artisanoscope
    wp_enqueue_style('single-artisan-style', get_stylesheet_directory_uri() .'/custom-css/single-artisan-style.css');
    wp_enqueue_style('content-single-product-style', get_stylesheet_directory_uri() .'/woocommerce/custom-css/content-single-product-style.css');
}

function artisanoscope_display_workshop_card_template(){
    echo("TEST 1 - La fonction se déclenche");
	global $product;
	$date = get_field("date");
	$startTime = get_field("heure_debut");
	$endTime = get_field("heure_fin");
	//$price = $product->get_price();

    if(!empty($date) && !empty($startTime) && !empty($endTime) && !empty($address) && !empty($price) && !empty($availabilities) && $availabilities > 0){
        echo("TEST 2 - Les conditions sont vérifiées");
        echo("<div class='artisan-workshops-card'>");
        echo("<a href='/produit/". $product->get_slug()."'>");
        echo $product->get_image();
        echo("<p class='artisan-workshops-card-info date'>".$date."</p>");
        echo("<h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>");
        echo("<p class='artisan-workshops-card-info'>".$startTime ." - ".$endTime."</p>");
        echo("<p class='artisan-workshops-card-info price'>".do_action( 'woocommerce_after_shop_loop_item_title' )."</p>");
        echo("</a>");
        echo("</div>");
    }
}