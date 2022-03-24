window.onload = function() {

    var selectorOrigen = document.getElementById('transferencia_compteOrigen');
    var selectorDesti =  document.getElementById('transferencia_compteDesti');

    var opt = document.createElement('option');
    opt.value = '-';
    opt.innerHTML = 'Codi Desti';
    opt.setAttribute('selected', true);

    selectorDesti.appendChild(opt);

    opt = document.createElement('option');
    opt.value = '-';
    opt.innerHTML = 'Codi Origen';
    opt.setAttribute('selected', true);
    selectorOrigen.appendChild(opt);
    selectorDesti.disabled = true;

    selectorOrigen.addEventListener("change", onchange);
    function onchange(){

        window.alert('changed');
        var selectorDesti =  document.getElementById('transferencia_compteDesti');
        var selectorOrigen = document.getElementById('transferencia_compteOrigen');

        // si el valor del select es distinto a Codi
        if (selectorOrigen.value != '-'){
            selectorDesti.disabled = false;
        }
        if (selectorOrigen.value == '-'){
            selectorDesti.disabled = true;
        }
    }
};

function validateForm() {

var selectorOrigen = document.getElementById('transferencia_compteOrigen');
var selectorDesti =  document.getElementById('transferencia_compteDesti')

    if (selectorOrigen.value == selectorDesti.value){

        window.alert('Transferencia no valida: el codi origen i desti son iguals.');
        return false;
    }
}

