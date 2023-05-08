setTimeout(function () {
    var divMessage = document.querySelector("#divMessage");
    var divMessage2 = document.querySelector("#divMessage2");

    divMessage.style.display = "none";
    divMessage2.style.display = "block";
}, 9000);

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

// Confirmation pour retirer un rendez-vous 
function retirer_rdv_admin(reservation_id)
{
    if (confirm("Êtes-vous certain de retirer le rendez-vous de cet utilisateur ? Il sera libéré pour une autre personne ou pourra être supprimé par la suite."))
    {
        document.location.href="../controlleur/controlleurTableaudebord.php?retirer_rdv=" + reservation_id;
    }
}
function retirer_rdv_uti(reservation_id)
{
    if (confirm("Êtes-vous certain de retirer ce rendez-vous de votre liste ? Il sera libéré pour une autre personne." + reservation_id))
    {
        document.location.href="../controlleur/controlleurTableaudebord.php?retirer_rdv=" + reservation_id;
    }
}