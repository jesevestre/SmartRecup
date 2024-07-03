// Gestion du bouton de validation
function text_plein() {
    var message = document.getElementById("message").value;

    // Si le champ message est rempli
    if (message != "") {
        BtnContact.classList.remove("disabled");
    }
}

// Gestion des caractères à mettre en majuscules
function premierCaractereMaj(texte) {
    let mots = texte.toLowerCase().split(" ");
    let array = [];
    
    mots.forEach(mot => {
        let premiereLettre = mot.charAt(0).toUpperCase();
        let remplacement = mot.replace(mot.charAt(0), premiereLettre);
        array.push(remplacement);
    });

    texte = array.join(" ");

    mots = texte.split("-");
    array = [];
    
    mots.forEach(mot => {
        premiereLettre = mot.charAt(0).toUpperCase();
        remplacement = mot.replace(mot.charAt(0), premiereLettre);
        array.push(remplacement);
    });

    return array.join("-");
}

// Gestion des spinners
// Activation du spinner si les champs nécessaires sont renseignés
function clickContact() {
    var email = document.getElementById("email").value;
    var prenom = document.getElementById("prenom").value;
    var nom = document.getElementById("nom").value;
    var titre = document.getElementById("titre").value;

    // Si les champs sont remplis
    if (email != "" && prenom != "" && nom != "" && titre != "") {
        $('#BtnContact i')
            .removeClass('fa-sharp fa-solid fa-arrow-right')
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