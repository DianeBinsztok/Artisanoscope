window.addEventListener('load', (event) => {
    let homeIcon = document.querySelector(".menu-item-86");
    let basketIcon = document.querySelector("#menu-1-b77e78e .menu-item-2656");
    let accountIcon = document.querySelector("#menu-1-b77e78e .menu-item-2658");
    homeIcon.classList.add("hide");
    basketIcon.classList.add("hide");
    accountIcon.classList.add("hide");
});

window.addEventListener('scroll', function() {
    let firstBasketIcon = document.querySelector("#menu-1-dc4f690 .menu-item-2656");
    let firstAccountIcon = document.querySelector("#menu-1-dc4f690 .menu-item-2658");

    let navMenuOnSticky = document.querySelector("#menu-1-b77e78e");
    let homeIcon = document.querySelector(".menu-item-86");
    let basketIcon = document.querySelector("#menu-1-b77e78e .menu-item-2656");
    let accountIcon = document.querySelector("#menu-1-b77e78e .menu-item-2658");

    if (navMenuOnSticky.getBoundingClientRect().y <= 42) { // Nombre de pixels de distance au bord haut
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