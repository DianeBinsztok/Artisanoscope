<?php

function artisanoscope_workshops_custom_filters() {
    // 2 - Les artisans
    // a - Si c'est un champs ACF (utiliser $artisan dans la boucle)
    $artisans=[];
    $args = array('post_type' => 'artisan', 'posts_per_page' => -1);
    $artisansRaw = get_posts($args);
    if ( $artisansRaw) {
        foreach($artisansRaw as $artisan){
            array_push($artisans, $artisan->post_title);
        }
    }
    
    // b - Si c'est une custom taxonomie (utiliser $artisan->name)
    //$artisans = get_terms('artisan', array('hide_empty' => false));

    // 3 - Les catégories d'artisanat
    $crafts=get_terms('product_cat', array('hide_empty' => true));

    echo('
        <div id="filterbar">
            <form id="formFilter" method="get" style="display:flex; justify-content:space-around;">
                <div id="artisan">
                    <select name="artisan" id="artisan" style="width:100%">
                    <option value="all">Tous les artisans</option>');

                    foreach ($artisans as $artisan){
                        echo('<option value="">'.$artisan.'</option>');
                    }

                    echo('
                    </select>
                </div>
                <div id="craft">
                    <select name="craft" id="craft">
                        <option value="all">Toutes les catégories</option>');

                        foreach ($crafts as $craft){
                            echo('<option value="">'.$craft->name.'</option>');
                        }
                    echo('</select>
                    </div>
            </form>
        </div>');  
                    /*echo('
                    </select>
                </div>
                <div id="date-interval">
                    <label for="date-interval">Pour une période donnée?</label>
                    <div id="date-inputs" >
                        <input type="date" id="start" name="start"
                        value="2023-03-22"
                        min="2023-03-22" max="2024-07-22">
                        <input type="date" id="end" name="end"
                        value="2023-03-22"
                        min="2023-03-22" max="2024-03-22">
                    <div>
                </div>
            </form>
        </div>');*/
}
  