console.log("TEST artisanoscopeDisplayWarningForChildrenTicket");
let selectInput = document.getElementById("participants");
let resetBtn = document.querySelector(".reset_variations");
selectInput.addEventListener("change", function(){
    let atelierOptionWarning = document.querySelector(".atelier-option-warning");
    let userChoice = document.getElementById("participants").value;
    if(userChoice == "enfant"){
        atelierOptionWarning.classList.remove("hide");
    }else{
        atelierOptionWarning.classList.add("hide");
    }
    resetBtn.addEventListener("click", function(){atelierOptionWarning.classList.add("hide");})
});
    

