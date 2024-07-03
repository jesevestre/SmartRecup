// Gestion du bouton de validation
function text_plein() {
    var email = document.getElementById("email").value;

    // Si le champ message est rempli
    if (email != "") {
        BtnMdpOublie.classList.remove("disabled");
    }
}

// Gestion des spinners
// Activation du spinner si les champs nécessaires sont renseignés
function clickContact() {
    var email = document.getElementById("email").value;

    // Si les champs sont remplis
    if (email != "") {
        $('#BtnMdpOublie i')
            .removeClass('fa-solid fa-unlock')
            .addClass('fa fa-spinner')
            .addClass('fa-spin');
    }
}

$('#BtnRetour').click(function(){
    $('#BtnRetour i')
        .removeClass('fa-sharp fa-solid fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});