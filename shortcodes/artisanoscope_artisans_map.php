<?php
//Carte des artisans
function artisanoscope_artisans_map(){
	$artisans = new WP_Query(array(
        'post_type' => 'artisan',
        'posts_per_page' => -1
    ));
	echo do_shortcode('[leaflet-map fitbounds zoom="20" height="500"]');
	while($artisans->have_posts()){
		$artisans->the_post();
		$location = get_field("art_adresse");
		$name = get_field("art_marque");
		$image = get_field("art_image");
		if(!empty($location)&&!empty($name)&&!empty($image)){
			echo do_shortcode("[leaflet-marker address='".$location."' visible] ".$name."<br/><img src='".$image['url']."' style:'width:50%; border-radius:50%'/>[/leaflet-marker]");
		}elseif(!empty($location)&&!empty($name)){
			echo (do_shortcode("[leaflet-marker address='".$location."' visible] ".$name."<br/>[/leaflet-marker]"));
		}
	}
}
add_shortcode('artisans-map', 'artisanoscope_artisans_map');
