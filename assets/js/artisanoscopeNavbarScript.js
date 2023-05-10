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
/*
window.addEventListener('scroll', function() {
    let navMenuOnSticky = document.querySelector("#menu-1-b77e78e");
    let homeIcon = document.querySelector(".menu-item-86");
    let basketIcon = document.querySelector("#menu-1-b77e78e .menu-item-2656");
    let accountIcon = document.querySelector("#menu-1-b77e78e .menu-item-2658");
    console.log(navMenuOnSticky.getBoundingClientRect().y);

    let scroll = window.scrollY;


    if (scroll >= 400) { // Nombre de pixels de défilement
        navMenuOnSticky.classList.add('sticky');
        homeIcon.classList.remove("hide");
        basketIcon.classList.remove("hide");
        accountIcon.classList.remove("hide");
    } else {
        navMenuOnSticky.classList.remove('sticky');
        homeIcon.classList.add("hide");
        basketIcon.classList.add("hide");
        accountIcon.classList.add("hide");
    }
});
*/



/*
window.addEventListener('load', (event) => {
    //TEST
    console.log("TEST");
    const navbar1 = document.querySelector(".artisanoscope-sticky-navbar");
    console.log(navbar1);
    navbar.classList.add("hide");

    let options = {
        root: window,
        rootMargin: '0px',
        threshold: 1.0
    }

    const navbarObserver1 = new IntersectionObserver(setVisibility(root), options);

    navbarObserver1.observe(navbar);


    //TEST
    const navbar = document.querySelector(".artisanoscope-sticky-navbar");
    navbar.classList.add("hide");
    const observer = new IntersectionObserver(e => {
            console.log(e);
            console.log(navbar);
            navbar.classList.toggle("hide", e.intersectionRatio < 1)
            //e[0].target.classList.toggle("hide", e.intersectionRatio < 1)
        },
        {
            threshold:0, 
        }
        );

        observer.observe(navbar);
    


//TEST
const navbarObserver = new IntersectionObserver(
([e]) => e.target.classList.toggle("hide", e.intersectionRatio < 1),
{ threshold: [1] }
);

//TEST
function setVisibility(target, visibility){
    if(visibility === true){
        target.classList.remove('hide');
    }else{
        target.classList.add('hide');
    }
}
});
*/