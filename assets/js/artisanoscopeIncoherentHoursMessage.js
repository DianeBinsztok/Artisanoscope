console.log("TEST artisanoscope Incoherent Hours Message");
let startTarget = document.querySelectorAll('input[type=text]')[10];
let endTarget = document.querySelectorAll('input[type=text]')[11];

console.log("startTarget => ", startTarget);
console.log("endTarget => ", endTarget);
console.log(" typeof(endTarget) => ", typeof(Date.parse(endTarget)));

endTarget.addEventListener("change", function(){
    console.log("startTarget.value => ", startTarget.value);
    console.log("endTarget.value => ", endTarget.value);

    if( Date.parse(startTarget.value) > Date.parse(endTarget.value)){
        alert("L'heure de début ne peut pas être postérieure à l'heure de fin!");
    }
});
