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
    event.preventDefault();
    if(startDate.value!==null){
        filterForm.submit();
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
  
