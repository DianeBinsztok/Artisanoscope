<?php
/**
 * La vignette de chaque produit dans la boucle archive-products.php
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$date = get_field("date");
$startTime = get_field("heure_debut");
$endTime = get_field("heure_fin");
//$price = $product->get_price();



// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>

<li <?php wc_product_class( '', $product ); ?>>
	<div class="artisan-workshops-card">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		//rend les vignettes ateliers cliquables
		do_action( 'woocommerce_before_shop_loop_item' );

		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		//Affiche les photos-miniatures
		do_action( 'woocommerce_before_shop_loop_item_title' );
		echo("<p class='artisan-workshops-card-info date'>".$date."</p>");
		/**
		 * Hook: woocommerce_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		//Affiche le titre du chaque produit

		//do_action( 'woocommerce_shop_loop_item_title' );
		//essayé d'ajouter mes classes css mais ne marche pas avec le hook => il semble surcharger mes classes
		echo("<h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>");

		echo("<p class='artisan-workshops-card-info'>".$startTime." - ".$endTime."</p>");
		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		//Affiche le prix
		do_action( 'woocommerce_after_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		//Affiche le bouton d'ajout au panier
		/*do_action( 'woocommerce_after_shop_loop_item' );*/
		?>
	</div>
</li>