let filterForm = document.querySelector("#artisanoscope-custom-filters-form");
let artisanOption = document.querySelector("#artisanoscope-artisan-filter");
let categoryOption = document.querySelector("#artisanoscope-craft-filter");
let startDate = document.querySelector('#artisanoscope-daterange-start');
let endDate = document.querySelector('#artisanoscope-daterange-end');

//Filtrer par artisan
artisanOption.addEventListener("change", function(event){
    event.preventDefault();
    filterForm.submit();
});
//Filtrer par artisanat
categoryOption.addEventListener("change", function(event){
    event.preventDefault();
    filterForm.submit();
});

//Filtrer par intervalle de dates
// 1 - toggle les inputs de dates
let dateToggle = document.querySelector('#artisanoscope-daterange-filter-button');
let datesZone = document.querySelector("#artisanoscope-daterange-toggle-zone");
dateToggle.addEventListener("click", function(event){
    toggleVisiblility(datesZone);
});

// 2 - envoi du formulaire si 2 dates sont saisies 
endDate.addEventListener("change", function(event){
    let missingDateMsg = document.querySelector("#artisanoscope-daterange-warning-missing-date");
    let incoherentDateMsg = document.querySelector("#artisanoscope-daterange-warning-incoherent-date");

    console.log("startDate.value => ", startDate.value)
    if(startDate.value !== ""){
        if(checkDates(startDate.value,endDate.value)){
            filterForm.submit();
        }else{
            setVisibility(incoherentDateMsg, true);
            setVisibility(missingDateMsg, false);
            console.log("Veuillez saisir une date de début antérieure à la date de fin");
        }
    }else{       
        setVisibility(missingDateMsg, true);
        setVisibility(incoherentDateMsg, false);
        console.log("Veuillez saisir une date de début");
    }
});

// 2-bis - si la date de fin est saisie avant la date de début
startDate.addEventListener("change", function(event){
    let missingDateMsg = document.querySelector("#artisanoscope-daterange-warning-missing-date");
    let incoherentDateMsg = document.querySelector("#artisanoscope-daterange-warning-incoherent-date");

    console.log("endDate.value => ", endDate.value)
    if(endDate.value !== ""){
        if(checkDates(startDate.value,endDate.value)){
            filterForm.submit();
        }else{
            setVisibility(incoherentDateMsg, true);
            setVisibility(missingDateMsg, false);
            console.log("Veuillez saisir une date de début antérieure à la date de fin");
        }
    }
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
//Réinitialiser les filtres
document.querySelector("#artisanoscope-reset-all-filters-button").addEventListener("click", function(event){
    artisanOption.value = "all";
    categoryOption.value = "all";
    startDate.value = null;
    endDate.value = null;
    event.preventDefault();
    filterForm.submit();
});
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
  
