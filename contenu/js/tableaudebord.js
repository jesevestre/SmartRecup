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
function retirerRdv(retirerRendezvousModal) {
    document.location.href="../controlleur/controlleurTableaudebord.php?retirerRendezvousModal=" + retirerRendezvousModal;
}

$('.btnRetirerRdv').click(function (e) {
    e.preventDefault();
    var $this = $(this);
    $('#btnSubmitRetirerRdv').data("idrdv", $this.data('idrdv'));
});

$('#btnSubmitRetirerRdv').click(function (e) {
    e.preventDefault();
    var $this = $(this);
    retirerRdv($this.data("idrdv"));
});

$('#btnCloseRetirerRdv').click(function (e) {
    e.preventDefault();
    $('#btnRetirerRdv').modal('hide');
});

// Commentaire des rendez-vous 
function voir_commentaire(commentaire)
{
    $('#btnVoirCommentaire').modal('show');
    $('#voirCommentaire').val(commentaire);
}

function editer_commentaire(commentaire, reservation_id)
{
    $('#btnEditerCommentaire').modal('show');
    $('#reservation_id').val(reservation_id);
    $('#voirCommentaireUti').val(commentaire);
}