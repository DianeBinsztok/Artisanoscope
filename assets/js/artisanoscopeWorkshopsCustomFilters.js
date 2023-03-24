document.querySelector("#artisanoscope-artisan-filter").addEventListener("change", function(event){
    event.preventDefault();
    document.querySelector("#artisanoscope-custom-filters-form").submit();
});
document.querySelector("#artisanoscope-craft-filter").addEventListener("change", function(event){
    event.preventDefault();
    document.querySelector("#artisanoscope-custom-filters-form").submit();
});