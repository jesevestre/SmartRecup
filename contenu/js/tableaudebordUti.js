setTimeout(function () {
    var divMessage = document.querySelector("#divMessage");
    var divMessage2 = document.querySelector("#divMessage2");

    divMessage.style.display = "none";
    divMessage2.style.display = "block";
}, 1000000);

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

// Confirmation pour retirer un rendez-vous pour le client
function retirerRdvUti(id_reservation, id_utilisateur, option_email_retirezRdv_prAdmin) {
    document.location.href = "../../controlleur/controlleurTableaudebordUti.php?retirezRendezvousModal=" + id_reservation + "&id_utilisateur=" + id_utilisateur + "&option_email_retirezRdv_prAdmin=" + option_email_retirezRdv_prAdmin;
}

$('.btnRetirerRdvUti').click(function (e) {
    e.preventDefault();
    var $this = $(this);

    idReservation1 = $this.data('ids');
    idReservation2 = idReservation1.split(' ');
    idReservation = idReservation2[0];
    idUtilisateur = idReservation2[1];
    option_email_retirezRdv_prAdmin = idReservation2[2];

    $('#btnSubmitRetirerRdvUti').data({
        idReservation: idReservation, 
        idUtilisateur: idUtilisateur,
        option_email_retirezRdv_prAdmin: option_email_retirezRdv_prAdmin,
    });
});

$('#btnSubmitRetirerRdvUti').click(function (e) {
    e.preventDefault();
    var $this = $(this);
    
    retirerRdvUti($this.data("idReservation"), $this.data("idUtilisateur"), $this.data("option_email_retirezRdv_prAdmin"));
});

$('#btnCloseRetirerRdvUti').click(function (e) {
    e.preventDefault();
    $('#btnRetirerRdvUti').modal('hide');
});

// Commentaire des rendez-vous 
function editer_commentaire(commentaireClient, reservation_id) {
    $('#btnEditerCommentaireClient').modal('show');
    $('#commentaireClient').val(commentaireClient);
    $('#reservation_id').val(reservation_id);
}

// Gestion du changement du mot de passe
function verifMdp() {
    var newMdp = document.getElementById("newMdp").value;
    var repeteMdp = document.getElementById("repeteMdp").value;
    var repeteMdpLength = repeteMdp.length;
    var texte = "";

    // Si le mdp de vérification n'est pas correct
    if (newMdp != repeteMdp) {
        texte = ('<div id="divMessage" style="display: block" class="alert alert-warning text-center small"><i class="fa fa-exclamation-triangle"></i> Le mot de passe de confirmation est différent.</div>');

        document.getElementById('texteErreur').innerHTML = texte;
        btnEditPwd.classList.add("disabled");

        // Si le mdp de vérification  est trop court
    } else if (repeteMdpLength < 5) {
        texte = ('<div id="divMessage" style="display: block" class="alert alert-warning text-center small"><i class="fa fa-exclamation-triangle"></i> Le mot de passe est trop court.</div>');

        document.getElementById('texteErreur').innerHTML = texte;
        inscription.classList.add("disabled");

        // Si le mdp de vérification est correct on libère me bouton pour s'inscrire
    } else {
        texte = ('<div id="divMessage" style="display: block" class="alert alert-success text-center small"><i class="fa fa-check"></i> Le nouveau mot de passe respect les conditions.</div>');

        document.getElementById('texteErreur').innerHTML = texte;
        btnEditPwd.classList.remove("disabled");
    }
}

// Gestion de l'oeil pour voir le mot de passe saisi
document.querySelectorAll(".toggle-password").forEach(v => {
    v.addEventListener("click", (e) => {
        let icon = e.target.querySelector(".fa");

        $(icon).toggleClass("fa-eye fa-eye-slash");
        let input = e.target.parentElement.parentElement.querySelector("input");
        if (input.getAttribute("type") == "password") {
            input.setAttribute("type", "text");
        } else {
            input.setAttribute("type", "password");
        }
    })
});

document.querySelectorAll(".toggle-fa").forEach(v => {
    v.addEventListener("click", (e) => {
        let icon = e.target.querySelector(".fa");

        $(e.target).toggleClass("fa-eye fa-eye-slash");
        let input = e.target.parentElement.parentElement.parentElement.parentElement.querySelector("input");
        if (input.getAttribute("type") == "password") {
            input.setAttribute("type", "text");
        } else {
            input.setAttribute("type", "password");
        }
    })
});

$(document).ready(function(){

    // Liste dynamique pour horaire massage
    $('#joursMassage').change(function(){
        var joursMassage_id = document.getElementById('joursMassage').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordUti.php',
            method: 'POST',
            data: {joursMassageID:joursMassage_id},
            success:function(data){
                $('#horairesMassage').html(data);
                document.getElementById('horairesMassage').disabled = false;
            },
        })
    })

    // Liste dynamique pour horaire machine
    $('#joursMachine').change(function(){
        var joursMachine_id = document.getElementById('joursMachine').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordUti.php',
            method: 'POST',
            data: {joursMachineID:joursMachine_id},
            success:function(data){
                $('#horairesMachine').html(data);
                document.getElementById('horairesMachine').disabled = false;
            },
        })
    })

    // Liste des clients pour les massages si sélectionner, champ Enregistrer, bouton perd son disabled
    $('#horairesMassage').change(function(){
        document.getElementById('prendreRendezvousModal1').disabled = false;
    })

    // Liste des clients pour les machines si sélectionner, champ Enregistrer, bouton perd son disabled
    $('#horairesMachine').change(function(){
        document.getElementById('prendreRendezvousModal2').disabled = false;
    })
})

// Gestion des spinners
$('#prendreRendezvousModal1').click(function(){
    $('#prendreRendezvousModal1 i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#prendreRendezvousModal2').click(function(){
    $('#prendreRendezvousModal2 i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#btnEditProfil').click(function(){
    $('#btnEditProfil i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#btnSubmitRetirerRdvUti').click(function(){
    $('#btnSubmitRetirerRdvUti i')
        .removeClass('fas fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});

$('#btnEditProfil').click(function(){
    $('#btnEditProfil i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#deconnexion').click(function(){
    $('#deconnexion i')
        .removeClass('fas fa-sign-out-alt')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});

$('#btnSubmitEditerCommentaireClient').click(function(){
    $('#btnSubmitEditerCommentaireClient i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});