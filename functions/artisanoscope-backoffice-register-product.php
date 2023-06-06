<?php

// I - Supprimer la metabox 'Description courte'
add_action('add_meta_boxes','artisanoscope_remove_short_desc_metabox', 40);
function artisanoscope_remove_short_desc_metabox(){
    remove_meta_box( 'postexcerpt','product','normal' );
}
add_action( 'woocommerce_variation_options_pricing', 'artisanoscope_add_custom_field_to_date_variations', 10, 3 );

// II - Données de produit - produit variable: nouveaux champs de variiton
// 1. Afficher les champs custom dans le backoffice Woocommerce, partie "Données de produit"
function artisanoscope_add_custom_field_to_date_variations( $loop, $variation_data, $variation ) {

    //Date
    woocommerce_wp_text_input( array(
        'id' => 'date[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple hide_if_course show_if_variation_virtual show_if_workshop',
        'type'  => 'date',
        'placeholder' => '', 
        'label' => __( 'Pour les ateliers ponctuels: Date de l\'ateler', 'woocommerce' ),
        'value' => get_post_meta($variation->ID, 'date', true ),
    ) );


    //Heure de début
    woocommerce_wp_text_input( array(
        'id' => 'start_hour[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'time',
        'label' => __( 'Heure de début', 'woocommerce' ),
        'value' => get_post_meta($variation->ID, 'start_hour', true ),
    ) );

    //Heure de fin
    woocommerce_wp_text_input( array(
        'id' => 'end_hour[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'time',
        'label' => __( 'Heure de fin', 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'end_hour', true )
    ) );

    //Lieu
    woocommerce_wp_radio(array(
        'id' => 'location[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'radio',
        'desc_tip' => true,
        'description'   => "Si l'atelier ou la formation ne se déroule pas au tiers-lieu, renseignez le champs suivant",

        'options'     => array(
            "La Baume d'Hostun" => __("La Baume d'Hostun", 'woocommerce' ),
            'autre' => __('Autre lieu:', 'woocommerce' ),
        ),
        'label' => __( 'Lieu', 'woocommerce' ),
        'value' => get_post_meta( $variation->ID, 'location', true )
    ));

    //Autre lieu (si l'atelier n'est pas à la Baume d'Hostun)
    woocommerce_wp_textarea_input( array(
        'id' => 'other_location[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'textarea',
        'label' => __( "Si l'atelier ou la formation ne se tient pas à la Baume d'Hostun, renseignez une adresse, en séparant chaque ligne par une virgule:", 'woocommerce' ),
        'desc_tip' => true,
        'description'   => "Vous pouvez renseigner une adresse avec des sauts de ligne",
        'value' => get_post_meta( $variation->ID, 'other_location', true )
    ) );
    
    /*CHAMPS ADDITIONNELS POUR LES FORMATIONS */
    /*
    //Date de début
    woocommerce_wp_text_input( array(
        'id' => 'start_date[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple hide_if_course show_if_variation_virtual show_if_workshop',
        'type'  => 'date',
        'placeholder' => '', 
        'label' => __( 'Pour les formations: date de début', 'woocommerce' ),
        'value' => get_post_meta($variation->ID, 'start_date', true ),
    ));
    //Date de fin
    woocommerce_wp_text_input( array(
        'id' => 'end_date[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple hide_if_course show_if_variation_virtual show_if_workshop',
        'type'  => 'date',
        'placeholder' => '', 
        'label' => __( 'Pour les formations: date de fin', 'woocommerce' ),
        'value' => get_post_meta($variation->ID, 'end_date', true ),
    ));

    //Périodicité
    woocommerce_wp_text_input( array(
        'id' => 'periodicity[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'text',
        'label' => __( "Périodicité", 'woocommerce' ),
        'desc_tip' => true,
        'description'   => "Par exemple: 'Tous les jeudis', 'Une fois par semaine', 'Une fois par mois', etc.",
        'value' => get_post_meta( $variation->ID, 'periodicity', true )
    ) );

    //Infos additionnelles
    woocommerce_wp_textarea_input( array(
        'id' => 'additional[' . $loop . ']',
        'class' => 'short',
        'wrapper_class'=>'form-row hide_if_simple show_if_variation_virtual show_if_workshop',
        'type'  => 'textarea',
        'label' => __( "Infos complémentaires", 'woocommerce' ),
        'desc_tip' => true,
        'description'   => "Mentionnez ici les informations qui ne figurent pas dans les autres champs",
        'value' => get_post_meta( $variation->ID, 'other_location', true )
    ) );
    */
}

// 2. Enregistrer les valeurs cutom saisies en même temps que la variation du produit
add_action( 'woocommerce_save_product_variation', 'artisanoscope_save_custom_fields_in_variation', 10, 2 );
//add_action( 'woocommerce_process_product_meta_workshop', 'artisanoscope_save_custom_fields_in_variation', 10, 2 );
function artisanoscope_save_custom_fields_in_variation( $variation_id, $i ) {
    // Tarif enfant
    /*
    $children_pricing_field = $_POST['children_pricing'][$i];
    if ( isset( $children_pricing_field ) ) update_post_meta( $variation_id, 'children_pricing', esc_attr( $children_pricing_field ) );
    */

    //Date au bon format
    $date_field = $_POST['date'][$i];
    if ( isset( $date_field ) ) update_post_meta( $variation_id, 'date', esc_attr( $date_field ) );

    //Heure de début
    $start_hour_field = $_POST['start_hour'][$i];
    if ( isset( $start_hour_field ) ) update_post_meta( $variation_id, 'start_hour', esc_attr( $start_hour_field ) );
    
    //Heure de fin
    $end_hour_field = $_POST['end_hour'][$i];
    if ( isset( $end_hour_field ) ) update_post_meta( $variation_id, 'end_hour', esc_attr( $end_hour_field ) );

    //Lieu
    $location_field = $_POST['location'][$i];
    if ( isset( $location_field ) ) update_post_meta( $variation_id, 'location', esc_attr( $location_field ) );

    //Autre lieu
    $other_location_field = $_POST['other_location'][$i];
    if ( isset( $other_location_field ) ) update_post_meta( $variation_id, 'other_location', esc_attr( $other_location_field ) );

    /*CHAMPS ADDITIONNELS POUR LES FORMATIONS*/
    /*
    //Date de début
    $start_date_field = $_POST['start_date'][$i];
    if ( isset( $start_date_field ) ) update_post_meta( $variation_id, 'start_date', esc_attr( $start_date_field ) );

    //Date de fin
    $end_date_field = $_POST['end_date'][$i];
    if ( isset( $end_date_field ) ) update_post_meta( $variation_id, 'end_date', esc_attr( $end_date_field ) );

    //Périodicité
    $periodicity_field = $_POST['periodicity'][$i];
    if ( isset( $periodicity_field ) ) update_post_meta( $variation_id, 'periodicity', esc_attr( $periodicity_field ) );

    //Infos complémentaires
    $additional_field = $_POST['additional'][$i];
    if ( isset( $additional_field ) ) update_post_meta( $variation_id, 'additional', esc_attr( $additional_field ) );
    */
}
    
// -----------------------------------------
// 3. Enregistrer le champs custom dans les données de variation de produit
add_filter( 'woocommerce_available_variation', 'artisanoscope_add_custom_fields_to_variation_data' );
function artisanoscope_add_custom_fields_to_variation_data( $variations ) {
    // C'est ce qui s'affichera en front office: Je retire les balises par défaut et ajouterai les miennes en front-office dans artisanoscope_display_date_options
    global $svg;
    global $product;

    // Date
    $dateField = strtotime(get_post_meta( $variations[ 'variation_id' ], 'date', true ));
    $variations['date'] = date('d/m/Y', $dateField);

    //Horaires
    $variations['start_hour'] = get_post_meta( $variations[ 'variation_id' ], 'start_hour', true );
    $variations['end_hour'] = get_post_meta( $variations[ 'variation_id' ], 'end_hour', true );

    //Lieu
    $location = "";
    $location_field=get_post_meta( $variations[ 'variation_id' ], 'location', true );
    if($location_field==="autre"){
        $location = get_post_meta( $variations[ 'variation_id' ], 'other_location', true );
    }else{
        $location = get_post_meta( $variations[ 'variation_id' ], 'location', true );
    }
    $variations['location'] = $location;

    /*POUR LES FORMATIONS*/
    
    //Dates 
    $startDateField = strtotime(get_post_meta( $variations[ 'variation_id' ], 'start_date', true ));
    $variations['start_date'] = date('d/m/Y', $startDateField);

    $endDateField = strtotime(get_post_meta( $variations[ 'variation_id' ], 'end_date', true ));
    $variations['end_date'] = date('d/m/Y', $endDateField);

    //Périodicité
    $variations['periodicity'] = get_post_meta( $variations[ 'variation_id' ], 'periodicity', true );

    //Infos complémentaires
    $variations['additional'] = get_post_meta( $variations[ 'variation_id' ], 'additional', true );

    // Les places disponibles (stock) pour chaque variations
    // 1 - Je récupère la variable Woocommerce $variations["availability_html"]
    $default_availability_tag = $variations["availability_html"];
    // 2 - je retire ses balises et styles par défaut
    $availability = str_replace('<p class="stock in-stock">','',
    $default_availability_tag);
    $availability = str_replace(' en stock</p>','',$availability);
    // 3 -  Je ne garde que le nombre
    $variations["availabilities"] = $availability;

    // Pareil pour le prix:
    // 1 - Je récupère la variable Woocommerce $variations["price_html"]
    $default_price_tag = $variations["price_html"];
    // 2 - je retire ses balises et styles par défaut
    $price = str_replace('<span class="price"><span class="woocommerce-Price-amount amount"><bdi>','',
    $default_price_tag);
    $price = str_replace('</span></span><span class="woocommerce-Price-currencySymbol">€</span></bdi>','',$price);
    // 3 -  Je ne garde que le nombre
    $variations["price"] = $price;

    return $variations;
}
    