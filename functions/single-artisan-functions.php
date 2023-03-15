<?php
//SINGLE-ARTISAN.PHP => ajouter les ateliers dans la page de l'artisan
function display_workshops_if_not_empty($workshops){
    if(!empty($workshops)){
        foreach ($workshops as $workshop){   
            $product = wc_get_product($workshop);
            if($product->is_visible()){
                $date=date('d/m/Y', strtotime($workshop->date));
                $startTime=substr($workshop->heure_debut, 0, -3);
                $endTime=substr($workshop->heure_fin, 0, -3);
                $product = wc_get_product($workshop);
                $productSlug = $product->get_slug();
    
                echo("<div class='artisan-workshops-card'>
                <a href='/produit/$productSlug'>
                ".$product->get_image()."
                <p class='artisan-workshops-card-info date'>$date</p>
                <h3 class='artisan-workshops-card-title'>".$product->get_name()."</h3>
                <p class='artisan-workshops-card-info'>$startTime - $endTime</p>
                <p class='artisan-workshops-card-info price'>");
                echo("</p></a></div>");
            }else{
                echo('<div class="artisan-workshops-card-inactive">
                        '.$product->get_image().'
                        <h3 class="artisan-workshops-card-title">'.$product->get_name().'</h3>
                        <div class="artisan-workshops-card-text"><p>Il n\'y a pas encore de date programmée pour cet atelier.</p><a href="mailto:communication@lartisanoscope.fr" class="artisan-info-card-shoplink">Contactez-nous pour en savoir plus</a></div>
                    </div>');
            }
        }
    }else{
        echo("Je n'organise pas d'atelier pour le moment");
    }
}
