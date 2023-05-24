let filterForm = document.querySelector("#artisanoscope-custom-filters-form");
let products = document.querySelectorAll(".artisanoscope-workshops-card");
let categoryOption = document.querySelector("#artisanoscope-craft-filter");
let startDate = document.querySelector('#artisanoscope-daterange-start');
let endDate = document.querySelector('#artisanoscope-daterange-end');
//let dateRangePicker = document.querySelector('#artisanoscope-daterange');
let ageOption = document.querySelector('#artisanoscope-age-filter-checkbox');
let beginnerOption = document.querySelector('#artisanoscope-beginner-friendly-filter-checkbox');
let availabilitiesOption = document.querySelector('#artisanoscope-availabilities-filter');

let filters = {
    "category" : {"active":false, "value":""},
    "dates":{"active":false, "values":{"start":"", "end":""}},
    "kidFriendly":{"active":false, "value":""},
    "beginnerFriendly":{"active":false, "value":""},
    "availabilities":{"active":false, "minValue":""},
};

// Afficher/cacher un produit
function setVisibility(target, visibility){
    if(visibility === true){
        target.classList.remove('hide');
    }else{
        target.classList.add('hide');
    }
}

// Comparer le stock d'un produit au critère du filtre "availabilities"
function sufficientNumber(criteria, number){
    return (number >= criteria);
}

// Vérifier que le stock d'un produit est suffisant
function matchesAvailabilityCriteria(product, availabilityCriteria){
    let classes =  product.classList;
    let stockClasses = [];
    for(let productClass of classes){
        if(productClass.includes("stock-")){
            stockClasses.push(productClass.replace("stock-", ""));
        }
    }
    return  stockClasses.find(className => sufficientNumber(parseInt(availabilityCriteria,10), parseInt(className,10)));
}

//Vérifier que le produit comporte une date comprise dans l'intervalle donné
function matchesDatesCriteria(product, startDate, endDate){
    let classes =  product.classList;
    let dateClasses = [];
    for(let productClass of classes){
        if(productClass.includes("date-")){
            dateClasses.push(productClass.replace("date-", ""));
        }
    }
    return  dateClasses.find(className => isInDateInterval(parseYMDDate(startDate), parseYMDDate(endDate), parseDMYDate(className)));
}

//Vérifier qu'une date est comprise dans un intervalle donné
function isInDateInterval(startDate, endDate, productDate){
    return (productDate>=startDate && productDate<=endDate);
}

function parseYMDDate(ymdDate) {
    const [year, month, day] = ymdDate.split('-').map(Number);
    return new Date(year, month - 1, day);
}
  
// Fonction pour convertir une date au format "dd/mm/yyyy" en objet Date
function parseDMYDate(dmyDate) {
    const [day, month, year] = dmyDate.split('/').map(Number);
    return new Date(year, month - 1, day);
}
//Vérifier que la saisie des dates est cohérente
function coherentDateInterval(startDateString,endDateString ){
    let startDate = Date.parse(startDateString);
    let endDate = Date.parse(endDateString);
    return startDate<=endDate;
}


// I - ARTISANAT
categoryOption.addEventListener("change", function(event){
    let categoySlug = event.target.value;
    if(categoySlug === "all"){
        filters.category.active = false;
        filters.category.value = "";
        setVisibilityOnEveryProducts(products);
        console.log(filters);
    }else{
        filters.category.active = true;
        filters.category.value = categoySlug;
        setVisibilityOnEveryProducts(products);

    }
});

// II - DATES
// 1 - Toggle les inputs de dates

let dateToggle = document.querySelector('#artisanoscope-daterange-filter-button');
let datesZone = document.querySelector("#artisanoscope-daterange-toggle-zone");
dateToggle.addEventListener("click", function(event){
datesZone.classList.toggle("hide");
});


/*
document.addEventListener('DOMContentLoaded', function() {
    // Déclenchez l'événement "show.daterangepicker" pour rendre le calendrier visible par défaut
    $(`#daterange`).trigger(`show.daterangepicker`);
});
$(function() {
    $(`input[name="daterange"]`).daterangepicker({
    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format(`YYYY-MM-DD`) + ` to ` + end.format(`YYYY-MM-DD`));
      console.log( start + " to " + end);
    });
});
*/

// AFFICHER LA SÉLECTION DE DATES SUR LE BOUTON
// 1 - Mettre les dates au bon format
function fuckJSDateFormats(rawDate){
    let day = rawDate.substring(8,10);
    let month= rawDate.substring(5,7);
    let year= rawDate.substring(0,4);
    return day+"/"+month+"/"+year;
}

// 2 - Afficher les dates à la sélection de la 2e
endDate.addEventListener("change", function(event){
    let incoherentDateMsg = document.querySelector("#artisanoscope-daterange-warning-incoherent-date");

    if(startDate.value != "" && endDate.value != ""){
        //Éviter une saisie de date incohérente
        if(coherentDateInterval(startDate.value,endDate.value)){

            filters.dates.active = true;
            filters.dates.values.start = startDate.value;
            filters.dates.values.end = endDate.value;
            //datesZone.classList.toggle("hide");
            setVisibility(datesZone, false);
            //changer l'affichage du bouton pour qu'il affiche les dates choisies, au bon format
            dateToggle.textContent = fuckJSDateFormats(startDate.value)+" → "+ fuckJSDateFormats(endDate.value);
            setVisibilityOnEveryProducts(products);
        }else{
            setVisibility(datesZone, false);
            setVisibility(incoherentDateMsg, true);
        }



    }else{
        filters.dates.active = false;
    }
});


// III - AGE
ageOption.addEventListener("click", function(event){
    if(this.checked){
        filters.kidFriendly.active = true;
        filters.kidFriendly.value = "kid-friendly";
        setVisibilityOnEveryProducts(products);
    }else if(!this.checked){
        filters.kidFriendly.active = false;
        filters.kidFriendly.value = "";
        setVisibilityOnEveryProducts(products);
    }
});

// IV - DEBUTANT
beginnerOption.addEventListener("click", function(event){
    if(this.checked){
        filters.beginnerFriendly.active = true;
        filters.beginnerFriendly.value = "beginner-friendly";
        setVisibilityOnEveryProducts(products);
        //console.log(filters);

    }else if(!this.checked){
        filters.beginnerFriendly.active = false;
        filters.beginnerFriendly.value = "";
        setVisibilityOnEveryProducts(products);
    }
});

// V - PLACES DISPONBLES
let availabilitiesToggle = document.querySelector("#artisanoscope-availabilities-filter-button");
availabilitiesToggle.addEventListener("click", function(){
    document.querySelector("#artisanoscope-availabilities-toggle-zone").classList.toggle("hide");
});

availabilitiesOption.addEventListener("change", function(event){
    event.preventDefault();
    filters.availabilities.active = true;
    filters.availabilities.minValue = availabilitiesOption.value;
    //console.log(filters);
    document.querySelector("#artisanoscope-availabilities-toggle-zone").classList.toggle("hide");
    if(parseInt(availabilitiesOption.value, 10) <= 1){
        availabilitiesToggle.textContent = availabilitiesOption.value+" place disponible";
    }else{
        availabilitiesToggle.textContent = availabilitiesOption.value+" places disponibles";
    }
    
    setVisibilityOnEveryProducts(products);
    console.log(filters);
})


function matchesAtLeastOneFilter(product){
    if(filters.category.active === true || filters.kidFriendly.active === true || filters.beginnerFriendly.active === true || filters.availabilities.active === true || filters.dates.active === true){
        if((filters.category.active === true && product.classList.contains(filters.category.value))||
        (filters.kidFriendly.active === true && product.classList.contains(filters.kidFriendly.value))||
        (filters.beginnerFriendly.active === true && product.classList.contains(filters.beginnerFriendly.value))||
        (filters.availabilities.active === true && matchesAvailabilityCriteria(product, filters.availabilities.minValue))||
        (filters.dates.active === true && matchesDatesCriteria(product, filters.dates.values.start,filters.dates.values.end))
        ){
            return true;
        }else{
            return false;
        }
    }else{
        return true;
    }
}

function setVisibilityOnEveryProducts(products){
    for(let product of products){
        if( matchesAtLeastOneFilter(product)){
            setVisibility(product.parentNode, true);
        }else{
            setVisibility(product.parentNode, false);
        }
    }
}

//Réinitialiser les filtres
document.querySelector("#artisanoscope-reset-all-filters-button").addEventListener("click", function(target){

    //RESET CATEGORIES
    filters.category.active = false;
    filters.category.value = "";
    categoryOption.value = "all";

    //RESET DATES
    filters.dates.active = false;
    dateToggle.textContent = "Dates";
    //setDefaultDates();


    //RESET AGES
    filters.kidFriendly.active = false;
    filters.kidFriendly.value = "";
    ageOption.checked = false; 

    //RESET DEBUTANT
    filters.beginnerFriendly.active = false;
    filters.beginnerFriendly.value = "";
    beginnerOption.checked = false;

    //RESET PLACES DISPONIBLES
    filters.availabilities.active = false;
    filters.availabilities.minValue = "";
    availabilitiesOption.value = 1;
    availabilitiesToggle.textContent = "Nombre de places disponibles";

    setVisibility(datesZone,false);
    setVisibility(document.querySelector("#artisanoscope-availabilities-toggle-zone"),false);

    
    setVisibilityOnEveryProducts(products);
});