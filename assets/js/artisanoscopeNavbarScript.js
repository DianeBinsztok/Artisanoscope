window.addEventListener('load', (event) => {
    let homeIcon = document.querySelector("#menu-item-86");
    let basketIcon = document.querySelector("#menu-item-4302");
    let accountIcon = document.querySelector("#menu-item-4305");

    if(homeIcon && basketIcon && accountIcon){
        homeIcon.classList.add("hide");
        basketIcon.classList.add("hide");
        accountIcon.classList.add("hide");
    }
});

window.addEventListener('scroll', function() {
    let firstBasketIcon = document.querySelector("#menu-1-dc4f690 .menu-item-2656");
    let firstAccountIcon = document.querySelector("#menu-1-dc4f690 .menu-item-2658");

    let navMenuOnSticky = document.querySelector(".elementor-element-3bb93a0");

    let homeIcon = document.querySelector("#menu-item-86");
    let basketIcon = document.querySelector("#menu-item-4302");
    let accountIcon = document.querySelector("#menu-item-4305");

    if (navMenuOnSticky.getBoundingClientRect().y <= 42) { // Nombre de pixels de distance au bord haut
        firstBasketIcon.classList.add("hide");
        firstAccountIcon.classList.add("hide");

        navMenuOnSticky.classList.add('sticky');
        homeIcon.classList.remove("hide");
        basketIcon.classList.remove("hide");
        accountIcon.classList.remove("hide");


        //Pour cibler et positionner les icônes e-commerce
        basketIcon.classList.add("account-pictogram-basket");
        accountIcon.classList.add("account-pictogram-account");

    } else {
        firstBasketIcon.classList.remove("hide");
        firstAccountIcon.classList.remove("hide");

        navMenuOnSticky.classList.remove('sticky');
        homeIcon.classList.add("hide");
        basketIcon.classList.add("hide");
        accountIcon.classList.add("hide");
    }
});
