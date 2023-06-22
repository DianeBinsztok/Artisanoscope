<?php

//TÂCHES JOURNALIÈRE: MISE À JOUR DU META "IMMINENCE" DE TOUS LES PRODUITS EN FONCTION DE LEUR META "REFERENCE_DATE"

// 1 - LES FONCTIONS: 
// a - Appeler la fonction artisanoscope_update_post_meta_imminence pour chaque produit
function artisanoscope_update_all_products_imminence() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $products = get_posts($args);
    foreach ($products as $product) {
        artisanoscope_create_post_meta_imminence($product->ID);
    }
}
// b - Vérifier le meta "imminence" pour tous les produits et déclencher l'envoi d'emails
function artisanoscope_check_all_products_imminence_and_send_emails() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $products = get_posts($args);
    foreach ($products as $product) {
        if($product->get_type() == "simple"){
            if(get_post_meta($product->ID, "imminence", true ) == "in-seven-days"){
                // trouver toutes les commandes finalisées qui contiennent ce produit
                // Pour chaque commande qui contient le produit: trigger l'envoi de l'email correspondant
            }
            if(get_post_meta($product->ID, "imminence", true ) == "in-one-day"){
                // trouver toutes les commandes finalisées qui contiennent ce produit
                // Pour chaque commande qui contient le produit: trigger l'envoi de l'email correspondant
            }
            if(get_post_meta($product->ID, "imminence", true ) == "passed-one-day"){
                // trouver toutes les commandes finalisées qui contiennent ce produit
                // Pour chaque commande qui contient le produit: trigger l'envoi de l'email correspondant
            }
        }elseif($product->get_type() == "variable"){
            $variations = $product->get_available_variations();
            foreach($variations as $variation){
                if(get_post_meta($variation["variation_id"], "imminence", true ) == "in-seven-days"){
                    // trouver toutes les commandes finalisées qui contiennent ce produit
                    // Pour chaque commande qui contient la variation du produit: trigger l'envoi de l'email correspondant
                }
                if(get_post_meta($variation["variation_id"], "imminence", true ) == "in-one-day"){
                    // trouver toutes les commandes finalisées qui contiennent ce produit
                    // Pour chaque commande qui contient la variation du produit: trigger l'envoi de l'email correspondant
                }
                if(get_post_meta($variation["variation_id"], "imminence", true ) == "passed-one-day"){
                    // trouver toutes les commandes finalisées qui contiennent ce produit
                    // Pour chaque commande qui contient la variation du produit: trigger l'envoi de l'email correspondant
                }
            }
        }
    }
}


// 2 - L'ÉVÉNEMENT: Créer un évènement journalier "artisanoscope_daily_event"
if (!wp_next_scheduled('artisanoscope_daily_event')) {
    wp_schedule_event(strtotime('midnight'), 'daily', 'artisanoscope_daily_event');
}

// 3 - Attacher les fonctions à l'événement
add_action('artisanoscope_daily_event', 'artisanoscope_update_all_products_imminence', 1);
add_action('artisanoscope_daily_event', 'artisanoscope_check_all_products_imminence_and_send_emails', 2);


//DÉTAIL: LES FONCTIONS APPELÉES

//Ajouter le champs meta "imminence" à un produit en fonction de sa date de référence
function artisanoscope_create_post_meta_imminence( $post_id ) {
    // Vérifier si le type de post est un produit
    if (get_post_type( $post_id ) !== 'product') {
        return; // Si le type de post n'est pas un produit, sortir de la fonction
    }

    $product = wc_get_product($post_id);
    /*
    if(!$product->get_virtual()){
        return;
    }
    */
    $type = $product->get_type();
    $format = get_field("prod_format", $post_id);

    if($type == "simple"){
        if(isset($format)&&!empty($format)){

            if($format == "ponctuel"){
                //Récupérer le timestamp à partir du champs "prod_date"
                $date = get_field("prod_date", $post_id);
                $date_object = DateTime::createFromFormat("d/m/Y", $date);
                $date_timestamp = $date_object->getTimestamp();

                //statut 
                //delete_post_meta($post_id, "imminence");
                update_post_meta($post_id, "imminence", get_workshop_imminence_data($date_timestamp));

            }elseif($format == "abonnement"){
                //Récupérer le timestamp à partir du champs "prod_date_debut"
                $date = get_field("prod_date_debut", $post_id);
                $date_object = DateTime::createFromFormat("d/m/Y", $date);
                $date_timestamp = $date_object->getTimestamp();

                //delete_post_meta($post_id, "imminence");
                update_post_meta($post_id, "imminence", get_workshop_imminence_data($date_timestamp));
            }else{
                return;
            }
        }else{
            return;
        }
    }elseif($type=="variable"){
        $variations = $product->get_available_variations();
        foreach($variations as $variation){
            $variation_ID = $variation["variation_id"];            
            $date = $variation["date"];
            $date_object = DateTime::createFromFormat("d/m/Y", $date);
            $date_timestamp = $date_object->getTimestamp();
            //delete_post_meta($variation_ID, "imminence");
            update_post_meta($variation_ID, "imminence", get_workshop_imminence_data($date_timestamp));
        }
    }
}
// Mettre à jour le post_meta chaque fois que le produit est créé ou sauvegardé
add_action( 'post_updated', 'artisanoscope_create_post_meta_imminence', 20 );
add_action( 'save_post', 'artisanoscope_create_post_meta_imminence', 20 );

//Assigner des valeurs au champs meta "imminence"
function get_workshop_imminence_data($reference_timestamp){
    $statuses = array("", "in-seven-days", "in-one-day", "passed-one-day");

    $reference_date = gmdate("d/m/Y", $reference_timestamp);

    if($reference_date == gmdate("d/m/Y", strtotime("+7 day"))){
        return $statuses[1];
    }elseif($reference_date == gmdate("d/m/Y", strtotime("+1 day"))){
        return $statuses[2];
    }elseif($reference_date == gmdate("d/m/Y", strtotime("-1 day"))){
        return $statuses[3];
    }else{
        return $statuses[0];
    }
}

//Ajouter le champs meta "reference_date" (pour ordonner chronologiquement les ateliers et déclencher les changement du champs "imminence")
function artisanoscope_create_post_meta_reference_date( $post_id ) {
    // Vérifier si le type de post est un produit
    if (get_post_type( $post_id ) !== 'product') {
        return; // Si le type de post n'est pas un produit, sortir de la fonction
    }

    $product = wc_get_product($post_id);
    /*
    if(!$product->get_virtual()){
        return;
    }
    */
    $type = $product->get_type();
    $format = get_field("prod_format", $post_id);

    if($type == "simple"){
        if(isset($format)&&!empty($format)){

            if($format == "ponctuel"){
                //Récupérer le timestamp à partir du champs "prod_date"
                $date = get_field("prod_date", $post_id);
                $date_object = DateTime::createFromFormat("d/m/Y", $date);
                $date_timestamp = $date_object->getTimestamp();

                //delete_post_meta($post_id, "reference_date");
                update_post_meta($post_id, "reference_date", $date_timestamp );

            }elseif($format == "abonnement"){
                //Récupérer le timestamp à partir du champs "prod_date_debut"
                $date = get_field("prod_date_debut", $post_id);
                $date_object = DateTime::createFromFormat("d/m/Y", $date);
                $date_timestamp = $date_object->getTimestamp();

                //delete_post_meta($post_id, "reference_date");
                update_post_meta($post_id, "reference_date", $date_timestamp );
            }else{
                return;
            }
        }else{
            return;
        }
    }elseif($type=="variable"){
        $variations = $product->get_available_variations();
        
        // 1 - La date de référence de chaque variation
        foreach($variations as $variation){
            $variation_ID = $variation["variation_id"];            
            $date = $variation["date"];
            $date_object = DateTime::createFromFormat("d/m/Y", $date);
            $date_timestamp = $date_object->getTimestamp();
            //delete_post_meta($variation_ID, "reference_date");
            update_post_meta($variation_ID, "reference_date", $date_timestamp );
        }

        // 2 - La date de référence globale
        //Récupérer le timestamp à partir du champs de variation "date" le plus imminent
        $date_timestamp = closest_date_variation($variations);
        //delete_post_meta($post_id, "reference_date");
        update_post_meta($post_id, "reference_date", $date_timestamp);
    }
}
// Mettre à jour le post_meta chaque fois que le produit est sauvegardé
add_action( 'post_updated', 'artisanoscope_create_post_meta_reference_date', 20 );
add_action( 'save_post', 'artisanoscope_create_post_meta_reference_date', 20 );

// Pour les produits variables: renvoie la variation la plus imminente
function closest_date_variation($variations){

    $closest_date = $variations[0]['date'];
    $closest_date_object = DateTime::createFromFormat("d/m/Y", $closest_date);
    $closest_date_timestamp = $closest_date_object->getTimestamp();

    foreach ( $variations as $variation ) {
        $date = $variation['date'];
        $date_object = DateTime::createFromFormat("d/m/Y", $date);
        $date_timestamp = $date_object->getTimestamp();
        

        if ( $date_timestamp < $closest_date_timestamp ) {
            $closest_date_timestamp = $date_timestamp;
        }
    }
    return $closest_date_timestamp;
}

// REQUÊTES DE COMMANDES
// Récupérer toutes les commandes qui contiennent un produit donné:
function get_orders_ids_by_product_id($product_id) {
 
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status = 'wc-completed'
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '".$product_id."'
        ORDER BY order_items.order_id DESC");
 
    return $results;
}
// Récupérer toutes les commandes qui contiennent une variation donnée de produit:
function get_orders_ids_by_variation_id($variation_id) {
 
    global $wpdb;
 
    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status = 'wc-completed'
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_variation_id'
        AND order_item_meta.meta_value = '".$variation_id."'
        ORDER BY order_items.order_id DESC");
 
    return $results;
}
