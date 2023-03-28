// Afficher un avertissement si l'utilisateur achète une place pour enfant
function artisanoscopeDisplayWarningForChildrenTicket(){
    console.log("TEST artisanoscopeDisplayWarningForChildrenTicket");
    let selectInput = document.getElementById("participants");
    selectInput.addEventListener("change", function(){
        let atelierOptionWarning = document.querySelector(".atelier-option-warning");
        let userChoice = document.getElementById("participants").value;
        if(userChoice == "enfant"){
            atelierOptionWarning.classList.remove("hide");
        }else{
            atelierOptionWarning.classList.add("hide");
        }
    });
}

// Afficher un avertissement à la saisie d'un nouvel atelier si les horaires sont incohérents
function artisanoscopeIncoherentScheduleMessage(){
    console.log("TEST artisanoscope Incoherent Hours Message");
    let startTarget = document.querySelector("#dp1678962246477");
    let endTarget = document.querySelector("#dp1678962246478");
    let message = "<p class='atelier-option-warning hide'> l'heure de début ne peut pas être postérieure à l'heure de fin!</p>";
    endTarget.appendChild(message);
    endTarget.addEventListener("change", function(){
        console.log("artisanoscope Incoherent Hours Message => click!");
        let atelierOptionWarning = document.querySelector(".atelier-option-warning");
        if(startTarget.value > endTarget.value){
            atelierOptionWarning.classList.remove("hide");
        }
    });
}