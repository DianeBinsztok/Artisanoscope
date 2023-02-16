<?php
get_header();
$artisan = get_post();
$marque = get_field('marque');
$nom = get_field('nom');
$image = get_field('image');
$portrait = get_field('portrait');
$image = get_field('image');
$introduction = get_field('introduction');
$presentation = get_field('presentation');
$lieuDeCreation= get_field('lieu-de-creation');
$shops = get_field('boutiques');
$otherShop = get_field('autre-boutique');
$status = get_field('statut');
$email=get_field('email');
$website=get_field('site-web');
$instagram=get_field('instagram');
$facebook=get_field('facebook');
$etsy=get_field('etsy');
$map=get_field('carte');
$stages = get_field('stage');
the_content();?>


<!--Partie haute: infos artisan-->
<section class="artisan-top-section">

    <!--Partie gauche-->
    <div class="artisan-top-section-left-column">

        <!--Nom de marque-->
        <h1 class="artisan-main-title"><? echo $marque?></h1>

        <!--Nom de l'artisan-->
        <!--<h2><? echo $nom ?></h2>-->

        <!--introduction:-->
        <div class="artisan-introduction"><? echo $introduction ?></div>

        <!--présentation:-->
        <div class="artisan-presentation"><? echo $presentation ?></div>
    </div>

    <!--Partie droite-->
    <div class="artisan-top-section-right-column">
        
        <!--image en avant:-->
        <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>"/>

        <!--encadré-->
        <div class="artisan-info-card">
            <div class="artisan-info-card-block">
                <h2 class="artisan-info-title">lieu de création</h2>
                <p><?= $lieuDeCreation ?></p>
            </div>
                

            <div class="artisan-info-card-block">
                <h2 class="artisan-info-title">points de vente</h2>
                <p>
                <? 
                    if (!empty($shops)) {
                    
                        foreach ($shops as $shop){ ?>
                            <?= $shop["value"].'<br/>';?>
                      <?}
                    } else {
                        echo "Pas de point de vente pour le moment";
                    }

                    if (!empty($otherShop)) {
                        echo $otherShop;
                    } ?>
                    
                </p>
                
            </div>


            <div class="artisan-info-card-block">
                <h2 class="artisan-info-title">statut</h2>
                <p><? echo $status ?></p>
            </div>


            <div>
                <h2 class="artisan-info-title">Stages et formations</h2>
                <ul class="artisan-workshops-list">
                    <? 
                    if (!empty($stages)) {
                        foreach ($stages as $stage){ 
                        $product = wc_get_product($stage);
                        $placesDisponibles= $product->get_stock_quantity();
                    ?>
                            <li>
                                <?php if(!empty($stage->date) && !empty($stage->heure_debut) && !empty($stage->heure_fin) && (!empty($stage->lieu)) && !empty($placesDisponibles) && $placesDisponibles > 0){?>
                                    <div class="artisan-workshops-card">
                                        <a href="/produit/<? echo $product->get_slug();?>">
                                            <? echo $product->get_image(); ?>
                                            <h3 class="artisan-workshops-card-title"><? echo $product->get_name();?></h3>
                                            <button class="artisan-workshops-card-btn">En savoir plus</button>
                                        </a>
                                    </div>
                                <?php }else{?>
                                    <div class="artisan-workshops-card">
                                        <? echo $product->get_image(); ?>
                                        <h3 class="artisan-workshops-card-title"><? echo $product->get_name();?></h3>
                                        <p>Il n'y a pas encore de date programmée pour cet atelier</p>
                                    </div>
                                <?php }?> 
                            </li>
                    <?  }
                    } else {
                        echo "Pas d'atelier de programmé pour le moment";
                    }?>
                </ul>
            </div>
        </div>
    </div>            
</section>

<!--Partie basses: infos de contact-->
<section class="artisan-bottom-contact-section">
    <h2 class="artisan-info-title">Contact:</h2>
    <div class="artisan-contact-icons">    
        <?php 
            if(!empty($email)){?>
                <a target="_blank" href="mailto:<?php echo $email ?>">
                    <svg width="41" height="33" viewBox="0 0 41 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41 4.125C41 1.85625 39.155 0 36.9 0H4.1C1.845 0 0 1.85625 0 4.125V28.875C0 31.1438 1.845 33 4.1 33H36.9C39.155 33 41 31.1438 41 28.875V4.125ZM36.9 4.125L20.5 14.4375L4.1 4.125H36.9ZM36.9 28.875H4.1V8.25L20.5 18.5625L36.9 8.25V28.875Z" fill="black"/>
                  </svg>
               </a>
        <?php }?>
        
        
        <?php 
            if(!empty($website)){?>
                <a target="_blank" href="<?php echo $website ?>">
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.5 0C22.3818 0 24.1969 0.240234 25.9453 0.720703C27.6937 1.20117 29.3286 1.88851 30.8501 2.78271C32.3716 3.67692 33.7529 4.74463 34.9941 5.98584C36.2354 7.22705 37.3031 8.61507 38.1973 10.1499C39.0915 11.6847 39.7788 13.3197 40.2593 15.0547C40.7397 16.7897 40.9867 18.6048 41 20.5C41 22.3818 40.7598 24.1969 40.2793 25.9453C39.7988 27.6937 39.1115 29.3286 38.2173 30.8501C37.3231 32.3716 36.2554 33.7529 35.0142 34.9941C33.7729 36.2354 32.3849 37.3031 30.8501 38.1973C29.3153 39.0915 27.6803 39.7788 25.9453 40.2593C24.2103 40.7397 22.3952 40.9867 20.5 41C18.6182 41 16.8031 40.7598 15.0547 40.2793C13.3063 39.7988 11.6714 39.1115 10.1499 38.2173C8.62842 37.3231 7.24707 36.2554 6.00586 35.0142C4.76465 33.7729 3.69694 32.3849 2.80273 30.8501C1.90853 29.3153 1.22119 27.687 0.740723 25.9653C0.260254 24.2437 0.0133464 22.4219 0 20.5C0 18.6182 0.240234 16.8031 0.720703 15.0547C1.20117 13.3063 1.88851 11.6714 2.78271 10.1499C3.67692 8.62842 4.74463 7.24707 5.98584 6.00586C7.22705 4.76465 8.61507 3.69694 10.1499 2.80273C11.6847 1.90853 13.313 1.22119 15.0347 0.740723C16.7563 0.260254 18.5781 0.0133464 20.5 0ZM20.5 38.4375C22.1416 38.4375 23.7231 38.224 25.2446 37.7969C26.7661 37.3698 28.1942 36.7692 29.5288 35.9951C30.8634 35.221 32.078 34.2801 33.1724 33.1724C34.2668 32.0646 35.201 30.8568 35.9751 29.5488C36.7492 28.2409 37.3564 26.8128 37.7969 25.2646C38.2373 23.7165 38.4508 22.1283 38.4375 20.5C38.4375 18.8584 38.224 17.2769 37.7969 15.7554C37.3698 14.2339 36.7692 12.8058 35.9951 11.4712C35.221 10.1366 34.2801 8.92204 33.1724 7.82764C32.0646 6.73324 30.8568 5.79899 29.5488 5.0249C28.2409 4.25081 26.8128 3.64355 25.2646 3.20312C23.7165 2.7627 22.1283 2.54915 20.5 2.5625C18.8584 2.5625 17.2769 2.77604 15.7554 3.20312C14.2339 3.63021 12.8058 4.23079 11.4712 5.00488C10.1366 5.77897 8.92204 6.71989 7.82764 7.82764C6.73324 8.93538 5.79899 10.1432 5.0249 11.4512C4.25081 12.7591 3.64355 14.1872 3.20312 15.7354C2.7627 17.2835 2.54915 18.8717 2.5625 20.5C2.5625 22.1416 2.77604 23.7231 3.20312 25.2446C3.63021 26.7661 4.23079 28.1942 5.00488 29.5288C5.77897 30.8634 6.71989 32.078 7.82764 33.1724C8.93538 34.2668 10.1432 35.201 11.4512 35.9751C12.7591 36.7492 14.1872 37.3564 15.7354 37.7969C17.2835 38.2373 18.8717 38.4508 20.5 38.4375ZM32.4517 20.8203L33.4126 17.9375H34.9141L33.2124 23.0625H31.7109L30.75 20.1797L29.7891 23.0625H28.2876L26.5859 17.9375H28.0874L29.0483 20.8203L30.0093 17.9375H31.4907L32.4517 20.8203ZM23.1626 17.9375H24.6641L22.9624 23.0625H21.4609L20.5 20.1797L19.5391 23.0625H18.0376L16.3359 17.9375H17.8374L18.7983 20.8203L19.7593 17.9375H21.2407L22.2017 20.8203L23.1626 17.9375ZM12.9126 17.9375H14.4141L12.7124 23.0625H11.2109L10.25 20.1797L9.28906 23.0625H7.7876L6.08594 17.9375H7.5874L8.54834 20.8203L9.50928 17.9375H10.9907L11.9517 20.8203L12.9126 17.9375Z" fill="black"/>
                    </svg>
                </a>
            <?php }?>
        
        
            <?php 
            if(!empty($facebook)){?>
                <a target="_blank" href="<?php echo $facebook ?>">
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41 20.5514C41 9.20702 31.816 0 20.5 0C9.184 0 0 9.20702 0 20.5514C0 30.4982 7.052 38.7805 16.4 40.6917V26.7168H12.3V20.5514H16.4V15.4135C16.4 11.4471 19.6185 8.22055 23.575 8.22055H28.7V14.386H24.6C23.4725 14.386 22.55 15.3108 22.55 16.4411V20.5514H28.7V26.7168H22.55V41C32.9025 39.9724 41 31.2175 41 20.5514Z" fill="black"/>
                    </svg>
                </a>
            <?php }?>
        
        
            <?php 
            if(!empty($instagram)){?>
                <a href="<?php echo $instagram ?>">
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.89 0H29.11C35.67 0 41 5.33 41 11.89V29.11C41 32.2634 39.7473 35.2877 37.5175 37.5175C35.2877 39.7473 32.2634 41 29.11 41H11.89C5.33 41 0 35.67 0 29.11V11.89C0 8.73658 1.25269 5.71231 3.4825 3.4825C5.71231 1.25269 8.73658 0 11.89 0ZM11.48 4.1C9.5227 4.1 7.64557 4.87753 6.26155 6.26155C4.87753 7.64557 4.1 9.5227 4.1 11.48V29.52C4.1 33.5995 7.4005 36.9 11.48 36.9H29.52C31.4773 36.9 33.3544 36.1225 34.7384 34.7384C36.1225 33.3544 36.9 31.4773 36.9 29.52V11.48C36.9 7.4005 33.5995 4.1 29.52 4.1H11.48ZM31.2625 7.175C31.9421 7.175 32.5939 7.44498 33.0745 7.92554C33.555 8.4061 33.825 9.05788 33.825 9.7375C33.825 10.4171 33.555 11.0689 33.0745 11.5495C32.5939 12.03 31.9421 12.3 31.2625 12.3C30.5829 12.3 29.9311 12.03 29.4505 11.5495C28.97 11.0689 28.7 10.4171 28.7 9.7375C28.7 9.05788 28.97 8.4061 29.4505 7.92554C29.9311 7.44498 30.5829 7.175 31.2625 7.175ZM20.5 10.25C23.2185 10.25 25.8256 11.3299 27.7478 13.2522C29.6701 15.1744 30.75 17.7815 30.75 20.5C30.75 23.2185 29.6701 25.8256 27.7478 27.7478C25.8256 29.6701 23.2185 30.75 20.5 30.75C17.7815 30.75 15.1744 29.6701 13.2522 27.7478C11.3299 25.8256 10.25 23.2185 10.25 20.5C10.25 17.7815 11.3299 15.1744 13.2522 13.2522C15.1744 11.3299 17.7815 10.25 20.5 10.25ZM20.5 14.35C18.8689 14.35 17.3046 14.9979 16.1513 16.1513C14.9979 17.3046 14.35 18.8689 14.35 20.5C14.35 22.1311 14.9979 23.6954 16.1513 24.8487C17.3046 26.0021 18.8689 26.65 20.5 26.65C22.1311 26.65 23.6954 26.0021 24.8487 24.8487C26.0021 23.6954 26.65 22.1311 26.65 20.5C26.65 18.8689 26.0021 17.3046 24.8487 16.1513C23.6954 14.9979 22.1311 14.35 20.5 14.35Z" fill="black"/>
                    </svg>
                </a>
            <?php }?>
        
        
            <?php 
            if(!empty($etsy)){?>
                <a target="_blank" href="<?php echo $etsy ?>">
                    <svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.7821 28.5323H9.69793V16.3538H15.7821M9.69793 22.4482H13.669M41.3438 43.3855H7.65626C7.11478 43.3855 6.59547 43.1704 6.21258 42.7875C5.8297 42.4046 5.61459 41.8853 5.61459 41.3438V7.65629C5.61459 7.11481 5.8297 6.5955 6.21258 6.21261C6.59547 5.82973 7.11478 5.61462 7.65626 5.61462H41.3438C41.8852 5.61462 42.4045 5.82973 42.7874 6.21261C43.1703 6.5955 43.3854 7.11481 43.3854 7.65629V41.3438C43.3854 41.8853 43.1703 42.4046 42.7874 42.7875C42.4045 43.1704 41.8852 43.3855 41.3438 43.3855Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M39.3021 25.5209V29.6042C39.3021 30.4164 38.9794 31.1954 38.4051 31.7697C37.8308 32.3441 37.0518 32.6667 36.2396 32.6667C35.4312 32.6633 34.6569 32.3404 34.0856 31.7684" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M39.3021 20.4677V25.5208C39.3021 26.333 38.9794 27.112 38.4051 27.6863C37.8308 28.2606 37.0518 28.5833 36.2396 28.5833C35.4273 28.5833 34.6484 28.2606 34.0741 27.6863C33.4997 27.112 33.1771 26.333 33.1771 25.5208V20.4677M24.6531 27.8483C25.0011 28.1293 25.4012 28.3389 25.8304 28.4651C26.2596 28.5912 26.7094 28.6314 27.1542 28.5833H27.8381C28.3796 28.5833 28.8989 28.3682 29.2818 27.9853C29.6647 27.6024 29.8798 27.0831 29.8798 26.5416C29.8798 26.0001 29.6647 25.4808 29.2818 25.098C28.8989 24.7151 28.3796 24.5 27.8381 24.5H26.4702C25.9287 24.5 25.4094 24.2849 25.0265 23.902C24.6436 23.5191 24.4285 22.9998 24.4285 22.4583C24.4285 21.9168 24.6436 21.3975 25.0265 21.0146C25.4094 20.6317 25.9287 20.4166 26.4702 20.4166H27.1542C28.0485 20.3069 28.9502 20.5525 29.6654 21.1006M19.2631 17.9564V27.0112C19.2563 27.2152 19.2905 27.4185 19.3639 27.609C19.4373 27.7994 19.5482 27.9732 19.6902 28.1199C19.8321 28.2666 20.0021 28.3832 20.19 28.4628C20.378 28.5424 20.58 28.5834 20.7842 28.5833H21.2333M17.6604 20.4677H20.8556" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php }?>
        
    </div>
</section>




<?php get_footer(); ?>

