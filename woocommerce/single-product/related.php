<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
  return;
}
/* START MODIF */
// Get ID of current product, to exclude it from the related products query
$current_product_id = $product->get_id();

$related = wc_get_related_products( $product->get_id(), $posts_per_page );
if ( ! $related /*= $product-> get_related( $posts_per_page )*/ ) {
  return;
}

$cats_array = array(0);

// get categories
$terms = wp_get_post_terms( $current_product_id, 'product_cat' );

// select only the category which doesn't have any children
foreach ( $terms as $term ) {
  $children = get_term_children( $term->term_id, 'product_cat' );
  if ( !sizeof( $children ) )
  $cats_array[] = $term->term_id;
}

$args = apply_filters( 'woocommerce_related_products_args', array(
  'post_type' => 'product',
  'post__not_in' => array( $current_product_id ),   // exclude current product
  'ignore_sticky_posts' => 1,
  'no_found_rows' => 1,
  'posts_per_page' => $posts_per_page,
  'orderby' => $orderby,
  'tax_query' => array(
    array(
        'taxonomy' => 'product_cat',
        'field' => 'id',
        'terms' => $cats_array
    ),
  )
));

$products                    = new WP_Query( $args );
$woocommerce_loop['name']    = 'related';
$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $columns );
/* END MODIF */


if ( $products->have_posts() ) : ?>

  <section class="related products">

    <?php
		//Changer le titre
        //$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
        $heading = apply_filters( 'woocommerce_product_related_products_heading', 'Ateliers similaires' );

    if ( $heading ) :
    ?>
      <h2><?php echo esc_html( $heading ); ?></h2>
    <?php endif; ?>
    
    <?php woocommerce_product_loop_start(); ?>

      <?php while ( $products->have_posts() ) : $products->the_post(); ?>

        <?php wc_get_template_part( 'content', 'product' ); ?>

      <?php endwhile; // end of the loop. ?>

    <?php woocommerce_product_loop_end(); ?>

  </section>

<?php endif;
//wp_reset_postdata();