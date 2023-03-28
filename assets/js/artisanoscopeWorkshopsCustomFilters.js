let filterForm = document.querySelector("#artisanoscope-custom-filters-form")

//Filtrer par artisan
document.querySelector("#artisanoscope-artisan-filter").addEventListener("change", function(event){
    event.preventDefault();
    filterForm.submit();
});
//Filtrer par artisanat
document.querySelector("#artisanoscope-craft-filter").addEventListener("change", function(event){
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
let startDate = document.querySelector('#artisanoscope-daterange-start');
let endDate = document.querySelector('#artisanoscope-daterange-end');
startDate.addEventListener("change", function(event){
    event.preventDefault();
    console.log("startDate's value => ",startDate.value);
});
endDate.addEventListener("change", function(event){
    event.preventDefault();
    console.log("endDate's value => ",endDate.value);
    if(startDate.value!==null){
        localStorage.setItem("user_selected_start", startDate.value); 
        localStorage.setItem("user_selected_end", endDate.value); 
        filterForm.submit();
        startDate.value =  localStorage.setItem("user_selected_start");
        endDate.value =  localStorage.setItem("user_selected_end");
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
  
