<?php
//SINGLE-ARTISAN.PHP => ajouter les ateliers dans la page de l'artisan
function artisanoscope_display_workshops($workshops){

    //Si l'artisan organise des ateliers
    if (!empty($workshops)) {
        //Rassembler les ids dans un tableau
        $workshops_ids = [];
        foreach($workshops as $workshop){
            array_push($workshops_ids, $workshop->ID);
        }
        //Requête des ateliers avec le tableau
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post__in' => $workshops_ids
        );
        $loop = new WP_Query($args);

        //Affichage
        while ($loop->have_posts()) : $loop->the_post();
            $post_id = get_the_ID();
            $product = wc_get_product($post_id);

            //Si le produit est visible au catalogue: afficher la vignette Woocommerce qui mène au produit
            $visibile = $product->get_catalog_visibility();
            $visibility = artisanoscope_control_product_before_set_visibility($visibile, $product->get_id());
            if($visibility=="visible"){
                wc_get_template_part('content', 'product');
                            
            //Si le produit est n'est pas visible au catalogue(problème de date, horaire ou autre): afficher une vignette d'annonce, non cliquable
            }else{
                echo("
                <li class='artisanoscope-workshops-card non-clickable'>
                    ".$product->get_image()."<br/>
                    <div class='artisanoscope-product-info-content'>
                        <h2 class='artisanoscope-workshops-card-title'>".$product->get_name()."</h2>
                        <p>Les dates seront proposées prochainement pour cet atelier</p>
                    </div>
                </li>
                ");
            }
                    
        endwhile;

        //Remise à zéro
        wp_reset_postdata();
    //Si l'artisan n'organise pas d'ateliers
    }else {
        echo "<p>Je n'organise pas encore d'atelier pour le moment</p>";
    }   
}