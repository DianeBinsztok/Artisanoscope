window.addEventListener('load', (event) => {

    //Icones Woocommerce du premier menu - disparaîtront au scroll
    let firstBasketIcon = document.querySelector(".menu-item-2656");
    let firstAccountIcon = document.querySelector(".menu-item-2658");

    //Icones Woocommerce et accueil du deuxième menu - apparaîtront au scroll
    let homeIcon = document.querySelector("#menu-item-86");
    let basketIcon = document.querySelector("#menu-item-4302");
    let accountIcon = document.querySelector("#menu-item-4403");

    if(homeIcon && basketIcon && accountIcon){
        homeIcon.classList.add("hide");
        basketIcon.classList.add("hide");
        accountIcon.classList.add("hide");
    }

    window.addEventListener('scroll', function() {
    
        let navMenuOnSticky = document.querySelector(".elementor-element-3bb93a0");
    
        //let homeIcon = document.querySelector("#menu-item-86");
        //let basketIcon = document.querySelector("#menu-item-4302");
        //let accountIcon = document.querySelector("#menu-item-4403");
    
        if (navMenuOnSticky.getBoundingClientRect().y <= 42) { //Nombre de pixels de distance au bord haut
            firstBasketIcon.classList.add("hide");
            firstAccountIcon.classList.add("hide");
    
            navMenuOnSticky.classList.add('sticky');
            homeIcon.classList.remove("hide");
            basketIcon.classList.remove("hide");
            accountIcon.classList.remove("hide");
    
        } else {
            firstBasketIcon.classList.remove("hide");
            firstAccountIcon.classList.remove("hide");
    
            navMenuOnSticky.classList.remove('sticky');
            homeIcon.classList.add("hide");
            basketIcon.classList.add("hide");
            accountIcon.classList.add("hide");
        }
    });
});


