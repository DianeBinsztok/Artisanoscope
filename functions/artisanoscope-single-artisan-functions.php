<?php
//SINGLE-ARTISAN.PHP => ajouter les ateliers dans la page de l'artisan
function display_workshops_if_not_empty($marque){
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $loop = new WP_Query($args);
    if ($loop->have_posts()) {
        while ($loop->have_posts()) : $loop->the_post();
        $artisan = get_field("prod_artisan");
        if(isset($artisan)&&!empty($artisan)){
            if($marque == $artisan->post_title){
                wc_get_template_part('content', 'product');
            }
        }
        endwhile;
    } else {
        echo __("Je n'organise pas d'atelier pour le moment");
    }
    wp_reset_postdata();
}