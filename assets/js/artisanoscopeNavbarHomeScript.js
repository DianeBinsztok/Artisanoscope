// Style spécial pour le header de l'acceuil

window.addEventListener('load', () => {

    //Les icônes de retour à l'accueil et Woocommerce
    let homeIcon = document.querySelector(".menu-item-86");
    let homepageBasketIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2656");
    let homepageAccountIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2658");

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

    //Et la pitite flèche
    let arrowIcons = icon.querySelectorAll(".elementskit-submenu-indicator");

    //Style du dernier onglet:
    let artisanLink = document.querySelector(".menu-item-3150");

});

window.addEventListener('scroll', () => {
    //Les icônes de retour à l'accueil et Woocommerce
    let firstBasketIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2656");
    let firstAccountIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2658");

    let navMenuOnSticky = document.querySelector("#menu-1-41e6d0d");

    let homeIcon = document.querySelector(".menu-item-86");
    let homepageBasketIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2656");
    let homepageAccountIcon = document.querySelector("#menu-1-41e6d0d .menu-item-2658");

    if (navMenuOnSticky.getBoundingClientRect().y <= 42) { // Nombre de pixels de distance au bord haut
                
        firstBasketIcon.classList.add("hide");
        firstAccountIcon.classList.add("hide");

        navMenuOnSticky.classList.add('sticky');
        
        homeIcon.classList.remove("hide");
        homepageBasketIcon.classList.remove("hide");
        homepageAccountIcon.classList.remove("hide");

        //Pour la couleur des onglets au survol
        let globalIcons = document.querySelectorAll(".ekit-menu-nav-link");
        for(let icon of globalIcons){
            icon.parentNode.classList.remove("homeicons-start-style");
        }
        
    } else {
        firstBasketIcon.classList.remove("hide");
        firstAccountIcon.classList.add("hide");

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