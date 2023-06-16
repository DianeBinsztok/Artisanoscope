<?php
get_header();
$artisan = get_post();
$marque = get_the_title( $artisan );
// Marche aussi:
//$marque = get_the_title( $post );
//$marque = get_field('art_marque');
$nom = get_field('art_nom');
$image = get_field('art_image');

$portrait = get_field('art_portrait_mini');
/*
if(empty($portrait)){
    $portrait = get_the_post_thumbnail(get_the_ID());
}
*/

$introduction = get_field('art_introduction');
$presentation = get_field('art_presentation');
$lieuDeCreation= get_field('art_lieu_creation');
$shops = get_field('art_boutiques');
$otherShop = get_field('art_autre_boutique');
$email=get_field('art_email');
$website=get_field('art_site_web');
$instagram=get_field('art_instagram');
$facebook=get_field('art_facebook');
$etsy=get_field('art_etsy');
$stages = get_field('art_ateliers');

the_content();?>

<? if(!empty($artisan)){?> 
<!--Conteneur-->
<div class="artisan-container">
    <!--Partie haute: infos artisan-->
    <div class="artisan-top-section">

        <!--Partie gauche-->
        <section class="artisan-section-presentation">

            <!--Nom de marque-->
            <? if(!empty($marque)){?>
            <h1 class="artisan-main-title"><? echo $marque?></h1>
            <?}?>

            <!--Photo et nom de l'artisan-->
            <div class="artisan-subtitle">
                <? if(!empty($portrait)){?>

                <img src="<?= esc_url($portrait['url']); ?>" alt="<?= esc_attr($portrait['alt']); ?>"/>
                <?}?>
                <? if(!empty($nom)){?> 
                    <div class="artisan-subtitle-text">Par <?= $nom ?></div>
                <?php } ?>
            </div>

            <!--introduction:-->
            <? if(!empty($introduction)){?>
            <div class="artisan-introduction"><?= $introduction ?></div>
            <?}else{?>
                <div class="artisan-introduction">Cet artisan n'a pas encore de présentation</div>
            <?}?>

            <!--présentation:-->
            <? if(!empty($presentation)){?>
            <div class="artisan-presentation"><?= $presentation ?></div>
            <?}?>
        </section>

        <!--Partie droite-->
        <section class="artisan-section-info">
            
            <!--image en avant:-->
            <? if(!empty($image)){?>
            <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>"/>
            <?}?>

            <!--encadré infos-->
            <div class="artisan-info-card">

                <!--Atelier-->
                <div class="artisan-info-card-block">
                    <h2 class="artisan-info-title">lieu de création</h2>
                    <? if(!empty($lieuDeCreation)){?>
                    <p><?= $lieuDeCreation ?></p>
                    <?}else{?>
                    <p>Ici et là ...</p>
                    <?}?>
                </div>
                    
                <!--Boutiques-->
                <div class="artisan-info-card-block">
                    
                    <h2 class="artisan-info-title">Points de vente</h2>
                    <ul class="artisan-info-card-shoplist">
                        <? 
                            if (!empty($shops)) {
                            
                                foreach ($shops as $shop){ 
                                    /*Affichage des boutiques dans des liens*/
                                    /*Direction vers des ancres dans la page "où nous trouver?"*/
                                    $value = $shop["value"];
                                    $shopLink = "";
                                    if($value == "Boutique de Valence"){
                                        $shopLink = "https://artisanoscope.ftalps.fr/ou-nous-trouver/#boutique-valence";
                                    }elseif($value == "Boutique de Romans-sur-Isère"){
                                        $shopLink = "https://artisanoscope.ftalps.fr/ou-nous-trouver/#boutique-romans";
                                    }
                                    ?>
                                    <li>
                                        <a class="artisan-info-card-shoplink" href=<?= $shopLink ?>>
                                            <?= $value ?>
                                        </a>
                                    </li>

                            <?}
                            } else {
                                echo "<p class='artisan-info-card-shoplist'>Pas de point de vente pour le moment</p>";
                            }

                            if (!empty($otherShop)) {
                                echo $otherShop;
                            } ?>   
                    </ul>                   
                </div>
                <!--Contact-->
                <div class="artisan-info-card-block">
                    <h2 class="artisan-info-title">Contact:</h2>
                    <div class="artisan-contact-icons">    

                        <?php 
                        if(!empty($email)||!empty($website)||!empty($facebook)||!empty($instagram)||!empty($etsy)){?>
                            <?php 
                                if(!empty($email)){?>
                                    <a target="_blank" href="mailto:<?php echo $email ?>">
                                        <?php echo(svg("email"))?>
                                    </a>
                            <?php }?>
                            
                            
                            <?php 
                                if(!empty($website)){?>
                                    <a target="_blank" href="<?php echo $website ?>">
                                    <?php echo(svg("website"))?>
                                    </a>
                                <?php }?>
                            
                            
                                <?php 
                                if(!empty($facebook)){?>
                                    <a target="_blank" href="<?php echo $facebook ?>">
                                    <?php echo(svg("facebook"))?>
                                    </a>
                                <?php }?>
                            
                            
                                <?php 
                                if(!empty($instagram)){?>
                                    <a target="_blank" href="<?php echo $instagram ?>">
                                    <?php echo(svg("instagram"))?>
                                    </a>
                                <?php }?>
                            
                            
                                <?php 
                                if(!empty($etsy)){?>
                                    <a target="_blank" href="<?php echo $etsy ?>">
                                    <?php echo(svg("etsy"))?>
                                    </a>
                                <?php }?>
                        <?}else{?>
                                <p>Pas d'informations de contact pour le moment ...</p>
                        <?}?>
                    </div>
                </div>
            </div>
        </section>            
    </div>

    <section class="artisan-workshops-section">
        <h2 class="artisan-info-title workshops-title">Ateliers et formations</h2>
        <ul class="artisan-workshops-list">
            <!-- Affichage des ateliers organisés par l'artisan-->
               <? 
                    artisanoscope_display_workshops($stages)
               ?>
            <!-- ateliers - fin-->
        </ul>
    </section>
</div>
<?}?>


<?php get_footer(); ?>

