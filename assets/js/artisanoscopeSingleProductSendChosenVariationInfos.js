
//Au clic sur une option de produit, ajouter aux inputs cachés les valeurs de l'option sélectionnée
document.addEventListener("DOMContentLoaded", function() {
    let options = document.querySelectorAll(".artisanoscope-product-option");
    let selectionInfos = document.querySelector(".artisanoscope-additional-infos");
    options.forEach(option=>{

        option.addEventListener("click", event=>{
            //Empêcher le rechargement de la page
            //event.preventDefault();
            selectionInfos.querySelector("#workshop-date").value=option.querySelector('.artisanoscope-product-option-title').textContent;
            selectionInfos.querySelector("#workshop-start_hour").value=option.querySelector('.artisanoscope-product-option-start-hour').textContent;
            selectionInfos.querySelector("#workshop-end_hour").value=option.querySelector('.artisanoscope-product-option-end-hour').textContent;
            selectionInfos.querySelector("#workshop-location").value=option.querySelector('.artisanoscope-product-option-location').textContent;
            console.log(selectionInfos);
        })
    }); 
  });