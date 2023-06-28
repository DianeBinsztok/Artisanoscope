// Style spécial pour le header de l'acceuil

window.addEventListener('load', () => {
    //Les premières icônes Woocommerce
    let firstBasketIcon = document.querySelector(".menu-item-2656");
    let firstAccountIcon = document.querySelector(".menu-item-2658");

    //Les icônes de retour à l'accueil et Woocommerce dans le deuxième menu, cachés au début
    let homeIcon = document.querySelector("#menu-item-86");
    let homepageBasketIcon = document.querySelector("#menu-item-4302");
    let homepageAccountIcon = document.querySelector("#menu-item-4403");

    if(homeIcon && homepageBasketIcon && homepageAccountIcon){
     
        homepageBasketIcon.classList.add("hide");
        homepageAccountIcon.classList.add("hide");
        homeIcon.classList.add("hide");
    }

    //Les icônes de navigation globales
    let globalIcons = document.querySelectorAll(".ekit-menu-nav-link");
    if(globalIcons){
        for(let icon of globalIcons){
            icon.parentNode.classList.add("homeicons-start-style");
        }
    }

    window.addEventListener('scroll', () => {

        //La barre de navigation
        let navMenuOnSticky = document.querySelector(".elementor-element-285cb31");

        //Les icônes de retour à l'accueil et Woocommerce de la barre de navigation
        //let homeIcon = document.querySelector("#menu-item-86");
        //let homepageBasketIcon = document.querySelector("#menu-item-4302");
        //let homepageAccountIcon = document.querySelector("#menu-item-4403");

        //Lorsque la barre de navigation atteint le haut de l'écran
        if (navMenuOnSticky.getBoundingClientRect().y <= 42) { // Nombre de pixels de distance au bord haut
                    
    
            //Cacher les premières icônes Woocommerce
            firstBasketIcon.classList.add("hide");
            firstAccountIcon.classList.add("hide");
        

            //Fixer la barre au haut de l'écran
            navMenuOnSticky.classList.add('sticky');

            //Révéler les icônes accueil et Woocommerce sur la barre de navigation
            
            homeIcon.classList.remove("hide");
            homepageBasketIcon.classList.remove("hide");
            homepageAccountIcon.classList.remove("hide");
        

            //Pour la couleur des onglets au survol
            //let globalIcons = document.querySelectorAll(".ekit-menu-nav-link");
            for(let icon of globalIcons){
                icon.parentNode.classList.remove("homeicons-start-style");
            }
            
        } else {

            navMenuOnSticky.classList.remove('sticky');
            //Au scroll de retour, revenir au style précédent
        
            firstBasketIcon.classList.remove("hide");
            firstAccountIcon.classList.remove("hide");

            navMenuOnSticky.classList.remove('sticky');
            homeIcon.classList.add("hide");
            homepageBasketIcon.classList.add("hide");
            homepageAccountIcon.classList.add("hide");
        

            //Pour la couleur des onglets au survol
            let globalIcons = document.querySelectorAll(".ekit-menu-nav-link");
            for(let icon of globalIcons){
                icon.parentNode.classList.add("homeicons-start-style");
            }
        }
    });
});