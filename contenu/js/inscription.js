// Gestion du bouton S'inscrire
function debloquerSinscrire() {
    var BtnInscription = document.getElementById("BtnInscription");

    BtnInscription.classList.remove("disabled");
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

// Activation du spinner si les champs nécessaires sont renseignés
function clickContact() {
    var email = document.getElementById("email").value;
    var prenom = document.getElementById("prenom").value;
    var nom = document.getElementById("nom").value;
    var cgu = document.getElementById("cgu").checked;

    // Si les champs sont remplis
    if (email != "" && prenom != "" && nom != "" && cgu == true) {
        $('#BtnInscription i')
            .removeClass('fa-sharp fa-solid fa-arrow-right')
            .addClass('fa fa fa-spinner')
            .addClass('fa-spin');
    }
}

// Gestion des spinners
$('#retour').click(function(){
    $('#retour i')
        .removeClass('fa-sharp fa-solid fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});
$('#BtnConnexion').click(function(){
    var emailConnexion = document.getElementById("emailConnexion").value;
    var mdpConnexion = document.getElementById("mdpConnexion").value;

    if (emailConnexion != "" && mdpConnexion != "") {
        $('#BtnConnexion i')
            .removeClass('fa-sharp fa-solid fa-arrow-right')
            .addClass('fa fa-spinner')
            .addClass('fa-spin');
    }
});

$('#retour2').click(function(){
    $('#retour2 i')
        .removeClass('fa-sharp fa-solid fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});
