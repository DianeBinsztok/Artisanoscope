<?php

/*CONTROLE DES CHAMPS*/

// 1 - Contrôler les champs requis pour les ateliers
function workshop_has_required_fields($date, $start_time, $end_time, $address, $productId){
  $product = wc_get_product($productId);
  $type = $product->get_type();
    //Pour les ateliers simples
    if($type == "simple"){
      $date = get_field("prod_date", $productId);
      return (!empty($date)&&!empty($start_time)&&!empty($end_time)&&!empty($address));
    }elseif($type == "variable"){
      //Pour les produits variable, s'assurer qu'il ont au moins une variation au format valide
      $variations = $product->get_available_variations();
      if (isset($variations)){
        foreach($variations as $variation){
          if(variation_has_all_required_fields($variation)){
            return true;
          }else{
            return false;
          }
        }
      }
    }
    return true;
}
// 2 - Contrôler les champs requis pour les formations
function course_has_required_fields($start_date, $end_date, $start_time, $end_time, $address, $productId){
  $start_date = get_field("prod_date_debut", $productId);
  $end_date = get_field("prod_date_fin", $productId);

  return (!empty($start_date)&&!empty($end_date)&&!empty($start_time)&&!empty($end_time)&&!empty($address));
}

// 3 - Contrôler la cohérence des champs pour les ateliers
function workshop_has_coherent_fields($date, $start_time, $end_time, $productId){
  $product = wc_get_product($productId);
  $type = $product->get_type();
    //Pour les ateliers simples
    if($type == "simple"){
      $date = get_field("prod_date", $productId);
      // Si la date est passée
      if(strtotime($date)<strtotime(date('d/m/Y'))){
        return false;
      }
      // Si l'horaire de début est postérieur à l'horaire de fin
      if(strtotime($start_time)>strtotime($end_time)){
        return false;
      }
      return true;
    }elseif($type == "variable"){
      //Pour les produits variable, s'assurer qu'il ont au moins une variation au format valide
      $variations = $product->get_available_variations();
      if (isset($variations)){
        foreach($variations as $variation){
          if(variation_has_coherent_fields($variation)){
            return true;
          }else{
            return false;
          }
        }
      }
    }
    return true;
}
// 4 - Contrôler la cohérence des champs pour les formations
function course_has_coherent_fields($start_date, $end_date, $start_time, $end_time, $productId){
  
  $start_date = get_field("prod_date_debut", $productId);
  $end_date = get_field("prod_date_fin", $productId);

  if(strtotime($start_date)<strtotime(date('d/m/Y'))){
    return false;
  }
  /*
  //section problématique
  if(strtotime($start_date)>strtotime($end_date)){
    return false;
  }
  //fin section problématique
  */

  if(strtotime($start_time)>strtotime($end_time)){
    return false;
  }
  
  return true;
  
}

/*DÉCIDER DE LA VISIBILITÉ DES PRODUITS AU CATALOGUE */
add_filter('woocommerce_product_is_visible', 'artisanoscope_control_product_before_set_visibility', 10, 2);
function artisanoscope_control_product_before_set_visibility($visible, $productId){
  $format = get_field("prod_format", $productId);
  $start_time = get_field("prod_heure_debut", $productId);
  $end_time = get_field("prod_heure_fin", $productId);
  $address = get_field("prod_lieu", $productId);

  //Empêcher l'affichage au catalogue des produits venant du logiciel de caisse et qui ne sont pas encore passés par la saise sur Woocommerce
  if(empty($format)){
    return false;
  }
  if($format == "ponctuel"){
    $date = get_field("prod_date", $productId);
    if(!workshop_has_required_fields($date, $start_time, $end_time,  $address, $productId)||!workshop_has_coherent_fields($date, $start_time, $end_time, $productId)){
      return false;
    }
  }elseif($format == "abonnement"){
    $start_date = get_field("prod_date_debut", $productId);
    $end_date = get_field("prod_date_fin", $productId);
    /*
    var_dump($start_date);
    var_dump($end_date);
    var_dump(strtotime($start_date));
    var_dump(strtotime($end_date));
    */
    if(!course_has_required_fields($start_date, $end_date, $start_time, $end_time,  $address, $productId)||!course_has_coherent_fields($start_date, $end_date, $start_time, $end_time, $productId)){
      return false;
    }
  }
    return $visible;
}