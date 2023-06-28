<?php

// Envoyer un email aux à tous les clients d'une liste de commandes donnée

// 1 - RAPPELS D'ATELIERS (dans sept jour ou dans un jour)

// a - Produits simples
function send_reminder_email_to_customers_by_product($orders_ids, $product){
    $product_id = $product->get_id();
    $format = get_field('prod_format', $product_id);
    $imminence = get_field('imminence', $product_id);


    // I - LE SUJET
    $subject = "Prêt.e pour votre atelier?";
    if( $format == "ponctuel"){
        if( $imminence == "in-seven-days"){
            $subject = "Prêt.e pour votre atelier dans sept jours?";
        }elseif($imminence == "in-one-day"){
            $subject = "Prêt.e pour votre atelier demain?";
        }
    }elseif($format == "abonnement"){
        if( $imminence == "in-seven-days"){
            $subject = "Prêt.e pour votre formation dans sept jours?";
        }elseif($imminence == "in-one-day"){
            $subject = "Prêt.e pour votre formation demain?";
        }
    }


    // II - LE CONTENU DE L'EMAIL
    $artisan = get_field('prod_artisan', $product_id)->marque;
    //$image = $product->get_image();
    $image_id = $product->get_image_id(); // Récupérer l'ID de l'image
    $image_url = wp_get_attachment_url($image_id); // Récupérer l'URL de l'image à partir de son ID

    $name= $product->get_name();

    $date = '';

    if($format == "ponctuel"){
        $date= get_field('prod_date', $product_id); 
    }elseif($format == "abonnement"){
        $date= get_field('prod_date_debut', $product_id); 
    }

    $start_hour= get_field('prod_heure_debut', $product_id); 
    $end_hour= get_field('prod_heure_fin', $product_id); 

    $location = '';
    $location_field= get_field('prod_lieu', $product_id);
    if($location_field=="hostun"){
        $location = "
        <span style = 'font-weight: 600;'>Le ChaluTiers-lieu</span><br/>
        31 Côte Simon,<br/>
        26730 La Baume d'Hostun";
    }elseif($location_field=="autre"){
        $location = break_line_on_comma(get_field('prod_autre_lieu', $product_id));
    }

    // Ne concerne que les produits qui possèdent un format et un type
    if(!$format){
        return;
    }

    // Pour chaque commande
    foreach($orders_ids as $order_id){
        $order = wc_get_order( $order_id );

        // III - LE DESTINATAIRE

        // Trouver le client
        $customer_name = $order->get_billing_first_name();

        // Récupérer l'e-mail
        $customer_email = $order->get_billing_email();

        // IV - ENVOYER L'EMAIL

        // Permettre l'affichage en html du template
        $content_type = function() { return 'text/html'; };
        add_filter( 'wp_mail_content_type', $content_type );

        // Envoyer les données dans le template d'email
        $message = workshop_reminder_email_template($customer_name, $artisan, $image_url, $name, $date, $start_hour, $end_hour, $location);

        // Envoyer l'email
        wp_mail( $customer_email, $subject, $message);

        // Remise à zéro du type de contenu
        remove_filter( 'wp_mail_content_type', $content_type );
    }
}

// b - Variation de produit
function send_reminder_email_to_customers_by_variation($orders_ids, $product, $variation){
    $product_id = $product->get_id();
    $format = get_field('prod_format', $product_id);
    $imminence = get_field('imminence', $product_id);

    // I - LE SUJET
    $subject = "Prêt.e pour votre atelier?";

        if( $imminence == "in-seven-days"){
            $subject = "Prêt.e pour votre atelier dans sept jours?";
        }elseif($imminence == "in-one-day"){
            $subject = "Prêt.e pour votre atelier demain?";
        }


    // II - LE CONTENU DE L'EMAIL

    // Envoyer les infos nécessaires au template du message
    $artisan = get_field('prod_artisan', $product_id)->marque;
    $image_id = $product->get_image_id();
    $image_url = wp_get_attachment_url($image_id);
    $name= $product->get_name();
    $date= $variation["date"]; 
    $start_hour= $variation["start_hour"]; 
    $end_hour= $variation["end_hour"]; 
    $location= $variation["location"];


    // Pour chaque commande
    foreach($orders_ids as $order_id){
        $order = wc_get_order( $order_id );

        // III - LE DESTINATAIRE

        // Trouver le client
        $customer_name = $order->get_billing_first_name();

        // Récupérer l'e-mail
        $customer_email = $order->get_billing_email();


        // IV - ENVOYER L'EMAIL

        // Permettre l'affichage en html du template
        $content_type = function() { return 'text/html'; };
        add_filter( 'wp_mail_content_type', $content_type );

        // Envoyer les données dans le template d'email
        $message = workshop_reminder_email_template($customer_name, $artisan, $image_url, $name, $date, $start_hour, $end_hour, $location);

        // Envoyer l'email
        wp_mail( $customer_email, $subject, $message);

        // Remise à zéro du type de contenu
        remove_filter( 'wp_mail_content_type', $content_type );
    }

}

// 2 - RÉCOLTER DES TÉMOIGNAGES DE RETOUR D'ATELIERS
// a - Produits simples
function send_followup_email_to_customers_by_product($orders_ids, $product){
    $product_id = $product->get_id();
    $format = get_field('prod_format', $product_id);

    // Ne concerne que les produits qui possèdent un format
    if(!$format){
        return;
    }

    // I - LE SUJET
    $subject = "Comment s'est passé votre atelier?";
    if( $format == "ponctuel"){
        $subject = "Comment s'est passé votre premier jour de formation?";
    }

    // II - LE CONTENU DE L'EMAIL
    $artisan = get_field('prod_artisan', $product_id)->marque;
    if(empty($artisan)){
        $artisan = "l'Artisanoscope";
    }
    $image_id = $product->get_image_id();
    $image_url = wp_get_attachment_url($image_id);
    $name= $product->get_name();

    // Pour chaque commande
    foreach($orders_ids as $order_id){
        $order = wc_get_order( $order_id );

        // III - LE DESTINATAIRE
    
        // Trouver le client
        $customer_name = $order->get_billing_first_name();
        // Récupérer l'e-mail
        $customer_email = $order->get_billing_email();


        // IV - ENVOYER L'EMAIL

        // Permettre l'affichage en html du template
        $content_type = function() { return 'text/html'; };
        add_filter( 'wp_mail_content_type', $content_type );

        // Envoyer les données dans le template d'email
        $message = workshop_followup_email_template($customer_name, $artisan, $image_url, $name);

        // Envoyer l'email
        wp_mail( $customer_email, $subject, $message);

        // Remise à zéro du type de contenu
        remove_filter( 'wp_mail_content_type', $content_type );
    }
}