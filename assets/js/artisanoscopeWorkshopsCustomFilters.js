let filterForm = document.querySelector("#artisanoscope-custom-filters-form");
//let artisanOption = document.querySelector("#artisanoscope-artisan-filter");
let categoryOption = document.querySelector("#artisanoscope-craft-filter");
let startDate = document.querySelector('#artisanoscope-daterange-start');
let endDate = document.querySelector('#artisanoscope-daterange-end');
let ageOption = document.querySelector('#artisanoscope-age-filter-checkbox');
let beginnerOption = document.querySelector('#artisanoscope-beginner-friendly-filter-checkbox');
let availabilitiesOption = document.querySelector('#artisanoscope-availabilities-filter');

//Filtrer par artisan
/*
artisanOption.addEventListener("change", function(event){
    event.preventDefault();
    filterForm.submit();
});
*/

// I - ARTISANAT
categoryOption.addEventListener("change", function(event){
    event.preventDefault();
    filterForm.submit();
});

// II - DATES
// 1 - Toggle les inputs de dates
let dateToggle = document.querySelector('#artisanoscope-daterange-filter-button');
let datesZone = document.querySelector("#artisanoscope-daterange-toggle-zone");
dateToggle.addEventListener("click", function(event){
    toggleVisiblility(datesZone);
});

// 2- Envoi du formulaire si 2 dates sont saisies 
// 2.1 - Les messages
let missingDateMsg = document.querySelector("#artisanoscope-daterange-warning-missing-date");
let incoherentDateMsg = document.querySelector("#artisanoscope-daterange-warning-incoherent-date");
// 2.2 - si la date de fin est saisie avant la date de début
endDate.addEventListener("change", function(event){
    if(startDate.value !== ""){
        if(checkDates(startDate.value,endDate.value)){
            filterForm.submit();
        }else{
            setVisibility(incoherentDateMsg, true);
            setVisibility(missingDateMsg, false);
        }
    }else{       
        setVisibility(missingDateMsg, true);
        setVisibility(incoherentDateMsg, false);
    }
});
// 2.3 - Si la date de début est saisie en premier 
startDate.addEventListener("change", function(event){
    if(endDate.value !== ""){
        if(checkDates(startDate.value,endDate.value)){
            filterForm.submit();
        }else{
            setVisibility(incoherentDateMsg, true);
            setVisibility(missingDateMsg, false);
        }
    }
    // ne pas afficher d'avertissement si la date de début est saisie après la date de fin
});

// toggle affichage
function toggleVisiblility(target){
    if(target.classList.contains("hide")){
        target.classList.remove("hide");
    }else{
        target.classList.add("hide");
    }
}
function setVisibility(target, visibility){
    if(visibility === true){
        target.classList.remove('hide');
    }else{
        target.classList.add('hide');
    }
}
// Vérifier la cohérence des dates
function checkDates(startDateString,endDateString ){
let startDate = Date.parse(startDateString);
//console.log(startDate);
let endDate = Date.parse(endDateString);
//console.log(endDate);
return startDate<=endDate;
}

// III - AGE
ageOption.addEventListener("click", function(event){
    /*
    if(this.checked){
        filterForm.submit();    
    }else if(!this.checked){
        filterForm.submit();
    }
    */
    filterForm.submit();
});

// IV - DEBUTANT
beginnerOption.addEventListener("click", function(event){
    filterForm.submit();
});

// V - PLACES DISPONBLES
let availabilitiesToggle = document.querySelector("#artisanoscope-availabilities-filter-button");
availabilitiesToggle.addEventListener("click", function(){
    toggleVisiblility(document.querySelector("#artisanoscope-availabilities-toggle-zone"));
});
availabilitiesOption.addEventListener("change", function(){
    filterForm.submit();
})

//Réinitialiser les filtres
document.querySelector("#artisanoscope-reset-all-filters-button").addEventListener("click", function(target){
    //artisanOption.value = "all";
    
    categoryOption.value = "all";
    startDate.value = "";
    endDate.value = "";
    ageOption.checked = false;
    beginnerOption.checked = false;
    availabilitiesOption.value = "";

    filterForm.submit();
});

// Effacer un paramètre de l'URL
function removeParam(key, sourceURL) {
    let rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (let i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

/*
  $(function() {

    $('input[name="dates"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Annuler',
            applyLabel: 'Appliquer',
        }
    });
    $('input[name="dates"]').on('apply.daterangepicker', function(event, picker) {
        event.preventDefault();
        console.log(' Du ' + picker.startDate.format('DD/MM/YYYY') + ' au ' + picker.endDate.format('DD/MM/YYYY'));
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        document.querySelector("#artisanoscope-custom-filters-form").submit();
    });
  
    $('input[name="dates"]').on('cancel.daterangepicker', function(event, picker) {
        event.preventDefault();
        $(this).val('Dates');
    });
  
  });
  */
  
// Tri des champs du formulaire avant soumission: d'envoyer que les valeurs des inputs activés
filterForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Empêcher la soumission par défaut

    const form = event.target;
    let url = form.action;
    let queryString = '';

    for (let i = 0; i < form.elements.length; i++) {
      const input = form.elements[i];

      if (input.name && input.value && input.value !== 'all') {
        queryString += (queryString ? '&' : '') + encodeURIComponent(input.name) + '=' + encodeURIComponent(input.value);
      }
    }

    if (queryString) {
      url += '?' + queryString;
    }
    form.action = url; // Modifier l'action du formulaire
    form.submit(); // Soumettre le formulaire modifié
  });