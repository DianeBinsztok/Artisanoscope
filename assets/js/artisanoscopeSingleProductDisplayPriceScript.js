//V2

//La div où sera affichée le texte:
let selectedOptionPriceDisplay = document.querySelector('#artisanoscope-selected-variation-price');

// Activer le plugin Yith (1ère fois)
let yithSelector = document.querySelector("#yith-wapo-2");
if(yithSelector){
    yithSelector.addEventListener("change", event=>{
        changePriceOnAgeSelection(yithSelector);
    })
}

//Sélection de variation
let productOptions = document.querySelectorAll(".artisanoscope-product-option");
productOptions.forEach(productOption=>{

    productOption.addEventListener("click", event=>{

        //Empêcher le rechargement de la page
        event.preventDefault();

        // Affecter l'ID de l'option à la valeur de l'input - au dessus du bouton d'ajout au panier
        let productOptionID = document.getElementsByName("variation_id")[0];
        let variationId = productOption.id;
        productOptionID.setAttribute("value", variationId);

        // Activer le bouton d'ajout au panier
        let addToCartBtn = document.querySelector(".single_add_to_cart_button");
        addToCartBtn.classList.remove("disabled");
        addToCartBtn.classList.remove("wc-variation-selection-needed");

        //Ajout d'une classe à l'option sélectionnée pour ciblage
            //Enlever la classe sur les autres options
        productOptions.forEach(option=>option.classList.remove("selected-variation"));
            //Ajouter la classe sur l'option choisie
        productOption.classList.add("selected-variation");

        //Récupération du prix de l'option sélectionnée
        let selectedOptionPrice = document.querySelector(".selected-variation .artisanoscope-product-option-price").textContent;

        selectedOptionPriceDisplay.textContent = selectedOptionPrice;

                //Remise: plugin Yith
        // Activer le plugin Yith (2ème fois)
        //let yithSelector = document.querySelector("#yith-wapo-2");
        if(yithSelector){
            //console.log("Yith selector");
            changePriceOnAgeSelection(yithSelector);
        }
    })
}); 


// Fonction pour changement de prix à la sélection de tarif
function changePriceOnAgeSelection(target){
    let selectedVariationPrice = document.querySelector(".selected-variation .artisanoscope-product-option-price").textContent;
    if(target.value==="1"){
        selectedOptionPriceDisplay.textContent =(parseInt(selectedVariationPrice, 10) - 10).toString()+",00 €";
    }else{
        selectedOptionPriceDisplay.textContent = selectedVariationPrice;
    }
}