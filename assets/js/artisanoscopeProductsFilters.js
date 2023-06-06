let filterForm = document.querySelector("#artisanoscope-custom-filters-form");
let products = document.querySelectorAll(".artisanoscope-workshops-card");
let categoryOption = document.querySelector("#artisanoscope-craft-filter");
let startDate = document.querySelector('#artisanoscope-daterange-start');
let endDate = document.querySelector('#artisanoscope-daterange-end');
//let ageOption = document.querySelector('#artisanoscope-age-filter-checkbox');
//let beginnerOption = document.querySelector('#artisanoscope-beginner-friendly-filter-checkbox');
let ageOption = document.querySelector('#artisanoscope-age-filter-button');
let beginnerOption = document.querySelector('#artisanoscope-beginner-friendly-filter-button');
let availabilitiesOption = document.querySelector('#artisanoscope-availabilities-filter');

//Le message d'erreur en cas de sélection de dates incohérentes
let incoherentDateMsg = document.querySelector("#artisanoscope-daterange-warning-incoherent-date");

//Les zones de saisie de dates et de places disponible: elles apparaissent au click sur le toggle correspondant

let availabitilyZone =  document.querySelector("#artisanoscope-availabilities-toggle-zone");
let datesZone = document.querySelector("#artisanoscope-daterange-toggle-zone");

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
/** SI INPUT DATES HTML */
/*
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
*/
/** SI INPUT DATES HTML _ FIN */

/** SI DATERANGEPICKER */
//Vérifier que le produit comporte une date comprise dans l'intervalle donné
function matchesDatesCriteria(product, startDate, endDate){
    let classes =  product.classList;
    let dateClasses = [];
    for(let productClass of classes){
        if(productClass.includes("date-")){
            dateClasses.push(productClass.replace("date-", ""));
        }
    }
    return  dateClasses.find(className => isInDateInterval(startDate, endDate, className));
}
/** SI DATERANGEPICKER - FIN */

//Vérifier qu'une date est comprise dans un intervalle donné
/** SI INPUTS HTML */
/*
function isInDateInterval(startDate, endDate, productDate){
    return (productDate>=startDate && productDate<=endDate);
}
*/
/** SI INPUTS HTML - FIN*/

/** SI DATERANGEPICKER */
function isInDateInterval(startDate, endDate, productDate){
    // Conversion de productDate en objet Moment
    let productDateMoment = moment(productDate, "DD/MM/YYYY");

    // Comparaison de productDateMoment avec startDate et endDate
    return (productDateMoment.isSameOrAfter(startDate) && productDateMoment.isSameOrBefore(endDate));
}
/** SI DATERANGEPICKER - FIN*/

function parseYMDDate(ymdDate) {
    const [year, month, day] = ymdDate.split('-').map(Number);
    return new Date(year, month - 1, day);
}
  
// Fonction pour convertir une date au format "d/m/Y" en objet Date
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
        this.classList.remove("filter-button-active");
        filters.category.active = false;
        filters.category.value = "";
        setVisibilityOnEveryProducts(products);
    }else{
        this.classList.add("filter-button-active");
        filters.category.active = true;
        filters.category.value = categoySlug;
        setVisibilityOnEveryProducts(products);

    }
});

// II - DATES
// 1 - Toggle les inputs de dates
let dateToggle = document.querySelector('#artisanoscope-daterange-filter-button');
//Si input html
/*
dateToggle.addEventListener("click", function(event){
    datesZone.classList.toggle("hide");
    setVisibility(availabitilyZone,false);
});
*/

//Si daterangepicker
dateToggle.addEventListener("click", function(event){
    //datesZone.classList.toggle("hide");
    setVisibility(availabitilyZone,false);
});

/*DATERANGE PICKER* */
document.addEventListener('DOMContentLoaded', function() {
    // Déclenchez l'événement "show.daterangepicker" pour rendre le calendrier visible par défaut
    /*
    $('#demo').daterangepicker({
    "locale": {
        "format": "MM/DD/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "startDate": "05/26/2023",
    "endDate": "06/01/2023"
}, function(start, end, label) {
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});
    */
    $('#artisanoscope-daterange-filter-button').daterangepicker({
        locale: {
          format: 'DD/MM/YYYY',
          applyLabel: 'Appliquer',
          cancelLabel: 'Annuler',
          fromLabel: 'De',
          toLabel: 'À',
          customRangeLabel: 'Personnaliser',
          weekLabel: 'S',
          daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],
          monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
          firstDay: 1
        }
    });
    $(`#artisanoscope-daterange-filter-button`).trigger(`show.daterangepicker`);

});
$(function() {
    $(`#artisanoscope-daterange-filter-button`).daterangepicker({
    }, function(start, end, label) {

      if(start.value != "" && end.value != ""){
            filters.dates.active = true;
            filters.dates.values.start = start;
            filters.dates.values.end = end;

            //changer l'affichage du bouton pour qu'il affiche les dates choisies, au bon format
            dateToggle.textContent = start.format(`DD/MM/YYYY`)+" → "+ end.format(`DD/MM/YYYY`);
            //Pour la couleur du bouton, si des dates sont sélectionnées
            dateToggle.classList.add("filter-button-active");

            setVisibility(datesZone, false);
            setVisibilityOnEveryProducts(products);
    }else{
        filters.dates.active = false;
    }
    });
});
/*FIN DATERENAGE PICKER* */

// AFFICHER LA SÉLECTION DE DATES SUR LE BOUTON
// 1 - Mettre les dates au bon format
function sloppyJSDateFormats(rawDate){
    let day = rawDate.substring(8,10);
    let month= rawDate.substring(5,7);
    let year= rawDate.substring(0,4);
    return day+"/"+month+"/"+year;
}

// 2 - Afficher les dates à la sélection de la 2e
/*INPUTS HTML* */
/*
endDate.addEventListener("change", function(event){
    if(startDate.value != "" && endDate.value != ""){
        //Éviter une saisie de date incohérente
        if(coherentDateInterval(startDate.value,endDate.value)){

            filters.dates.active = true;
            filters.dates.values.start = startDate.value;
            filters.dates.values.end = endDate.value;
            //datesZone.classList.toggle("hide");
            setVisibility(datesZone, false);
            //changer l'affichage du bouton pour qu'il affiche les dates choisies, au bon format
            dateToggle.textContent = sloppyJSDateFormats(startDate.value)+" → "+ sloppyJSDateFormats(endDate.value);
            setVisibilityOnEveryProducts(products);
        }else{
            setVisibility(datesZone, false);
            setVisibility(incoherentDateMsg, true);
        }
    }else{
        filters.dates.active = false;
    }
});
*/
/*FIN INPUTS HTML* */

// III - PLACES DISPONBLES
let availabilitiesToggle = document.querySelector("#artisanoscope-availabilities-filter-button");
availabilitiesToggle.addEventListener("click", function(){
    availabitilyZone.classList.toggle("hide");
    //cacher la zone de dates si elle est affichée
    setVisibility(datesZone, false);
});

availabilitiesOption.addEventListener("change", function(event){
    event.preventDefault();
    availabilitiesToggle.classList.add("filter-button-active");
    filters.availabilities.active = true;
    filters.availabilities.minValue = availabilitiesOption.value;
    //document.querySelector("#artisanoscope-availabilities-toggle-zone").classList.toggle("hide");
    if(parseInt(availabilitiesOption.value, 10) <= 1){
        availabilitiesToggle.textContent = availabilitiesOption.value+" place disponible";
    }else{
        availabilitiesToggle.textContent = availabilitiesOption.value+" places disponibles";
    }
    
    setVisibilityOnEveryProducts(products);
})

/** CHANGEMENT DE VALEUR AU CLICK SUR LES BOUTONS + ET -*/
let quantityButtons = document.querySelectorAll('.quantity-button');
for (let button of quantityButtons) {
    button.addEventListener('click', function() {

        if (this.classList.contains('quantity-add')) {
            let input = this.parentElement.querySelector('input');
            let addValue = parseInt(input.value, 10) + 1;
            input.value = addValue;
            
            let changeEvent = new Event('change');
            input.dispatchEvent(changeEvent);
            
        }

        if (this.classList.contains('quantity-remove')) {
            let input = this.parentElement.querySelector('input');
            let removeValue = parseInt(input.value, 10) - 1;
            if (removeValue === 0) {
                removeValue = 1;
            }
            input.value = removeValue;
            
            let changeEvent = new Event('change');
            input.dispatchEvent(changeEvent);
        }
    });
}
let quantityInputs = document.querySelectorAll('.quantity input');
for (let input of quantityInputs) {
    input.addEventListener('change', function() {
    });
}
/** FIN*/

/** CACHER LA ZONE D'INPUT AU CLICK EN DEHORS DE LA ZONE*/
document.addEventListener('click', function(event) {

    // Vérification si le clic s'est produit à l'intérieur de la zone de basculement
    if (!availabitilyZone.contains(event.target)) {
      
      // Si le clic s'est produit à l'extérieur de la zone de basculement,
      // vérifier qu'il ne s'est pas produit sur le bouton qui contrôle la zone de basculement
      let button = document.getElementById('artisanoscope-availabilities-filter-button');
      if (event.target !== button) {
        
        // Si le clic s'est produit à l'extérieur de la zone de basculement et n'était pas sur le bouton,
        // alors masquer la zone de basculement
        availabitilyZone.classList.add('hide');
      }
    }
  });
/** FIN */

// IV - AGE
ageOption.addEventListener("click", function(event){
    this.classList.toggle("age-filter-active");
    if(this.classList.contains("age-filter-active")){
        filters.kidFriendly.active = true;
        filters.kidFriendly.value = "kid-friendly";
        setVisibilityOnEveryProducts(products);
    }else{
        filters.kidFriendly.active = false;
        filters.kidFriendly.value = "";
        setVisibilityOnEveryProducts(products);
    }
});
/*
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
*/

// V - DEBUTANT
beginnerOption.addEventListener("click", function(event){
    this.classList.toggle("beginner-filter-active");
    if(this.classList.contains("beginner-filter-active")){
        filters.beginnerFriendly.active = true;
        filters.beginnerFriendly.value = "beginner-friendly";
        setVisibilityOnEveryProducts(products);
    }else{ filters.beginnerFriendly.active = false;
        filters.beginnerFriendly.value = "";
        setVisibilityOnEveryProducts(products);}
});
/*
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
*/


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
    setVisibility(incoherentDateMsg, false);
    for(let product of products){
        if( matchesAtLeastOneFilter(product)){
            setVisibility(product.parentNode, true);
        }else{
            setVisibility(product.parentNode, false);
        }
    }
}

//VI - RÉINITIALISER
document.querySelector("#artisanoscope-reset-all-filters-button").addEventListener("click", function(target){

    //RESET CATEGORIES
    filters.category.active = false;
    filters.category.value = "";
    categoryOption.value = "all";
    categoryOption.classList.remove("filter-button-active");

    //RESET DATES
    filters.dates.active = false;
    dateToggle.classList.remove("filter-button-active"); 
    dateToggle.textContent = "Dates";
    //setDefaultDates();


    //RESET AGES
    filters.kidFriendly.active = false;
    filters.kidFriendly.value = "";
    ageOption.classList.remove("age-filter-active"); 

    //RESET DEBUTANT
    filters.beginnerFriendly.active = false;
    filters.beginnerFriendly.value = "";
    beginnerOption.classList.remove("beginner-filter-active"); 


    //RESET PLACES DISPONIBLES
    filters.availabilities.active = false;
    filters.availabilities.minValue = "";
    availabilitiesOption.value = 1;
    availabilitiesToggle.classList.remove("filter-button-active"); 
    availabilitiesToggle.textContent = "Places disponibles";

    setVisibility(datesZone,false);
    setVisibility(availabitilyZone,false);
    setVisibilityOnEveryProducts(products);
});