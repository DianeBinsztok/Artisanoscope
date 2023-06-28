<?php
// I - LES FONCTIONS 
// 1 - mettre à jour le post_meta 'imminence' pour chaque produit
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

// 2 - Vérifier le meta "imminence" pour tous les produits et déclencher l'envoi d'emails
function artisanoscope_check_all_products_imminence_and_send_emails() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);

    foreach ($posts as $post) {
        /*get_posts() renvoie des objets WP_Post: 
        il faut les traduire en objets WC_Product pour utiliser get_type() et autres méthodes des produits Woocommerce*/
        $post_id = $post->ID;
        $product=wc_get_product( $post_id );
        $product_id = $product->get_id();
        

        if($product->get_type() == "simple"){
            if(get_post_meta($product_id, "imminence", true ) == "in-seven-days" || get_post_meta($product_id, "imminence", true ) == "in-one-day"){

                // Pour toutes les commandes finalisées qui contiennent ce produit
                $orders_ids = get_orders_ids_by_product_id($product_id);

                // Envoyer un email de rappel
                send_reminder_email_to_customers_by_product($orders_ids, $product);
            }
            if(get_post_meta($product_id, "imminence", true ) == "passed-one-day"){

                // Pour toutes les commandes finalisées qui contiennent ce produit
                $orders_ids = get_orders_ids_by_product_id($product_id);

                // Envoyer un email pour récolter des avis
                send_followup_email_to_customers_by_product($orders_ids, $product);
            }
        }elseif($product->get_type() == "variable"){
            $variations = $product->get_available_variations();
            foreach($variations as $variation){
                $variation_id = $variation["variation_id"];
                if(get_post_meta($variation_id, "imminence", true ) == "in-seven-days" || get_post_meta($variation_id, "imminence", true ) == "in-one-day"){

                    // Pour toutes les commandes finalisées qui contiennent ce produit
                    $orders_ids = get_orders_ids_by_variation_id($variation_id);

                    // Envoyer un email de rappel
                    send_reminder_email_to_customers_by_variation($orders_ids, $product, $variation);
                }
                if(get_post_meta($variation_id, "imminence", true ) == "passed-one-day"){
                    
                    // Pour toutes les commandes finalisées qui contiennent ce produit
                    $orders_ids = get_orders_ids_by_variation_id($variation_id);

                    // Envoyer un email pour récolter des avis
                    send_followup_email_to_customers_by_product($orders_ids, $product);
                }
            }
        }
    }
}

// II - L'ÉVÉNEMENT: Créer un évènement journalier "artisanoscope_daily_event"
if (!wp_next_scheduled('artisanoscope_daily_event')) {
    wp_schedule_event(strtotime('midnight'), 'daily', 'artisanoscope_daily_event');
}

// III - ATTACHER LES FONCTIONS À L'ÉVÉNEMENT
add_action('artisanoscope_daily_event', 'artisanoscope_update_all_products_imminence', 1);
add_action('artisanoscope_daily_event', 'artisanoscope_check_all_products_imminence_and_send_emails', 2);


//FONCTIONS APPELÉES

// Récupérer toutes les commandes qui contiennent un produit donné:
function get_orders_ids_by_product_id($product_id) {
 
    global $wpdb;

    $query = $wpdb->prepare("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status = 'wc-completed'
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = %d
        ORDER BY order_items.order_id DESC", $product_id);
        
    $results = $wpdb->get_col($query);
 
    return $results;
}

// Récupérer toutes les commandes qui contiennent une variation donnée de produit:
function get_orders_ids_by_variation_id($variation_id) {
 
    global $wpdb;

    $query = $wpdb->prepare("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status = 'wc-completed'
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_variation_id'
        AND order_item_meta.meta_value = %d
        ORDER BY order_items.order_id DESC", $variation_id);
        
    $results = $wpdb->get_col($query);
 
    return $results;
}
