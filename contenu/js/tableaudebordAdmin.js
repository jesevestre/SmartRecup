setTimeout(function () {
    var divMessage = document.querySelector("#divMessage");
    var divMessage2 = document.querySelector("#divMessage2");

    divMessage.style.display = "none";
    divMessage2.style.display = "block";
}, 1000000);

// Confirmation pour retirer un rendez-vous pour les admins
function retirerRdvAdmin(id_reservation, id_utilisateur) {
    document.location.href="../../controlleur/controlleurTableaudebordAdmin.php?retirerRendezvousModal=" + id_reservation + "&id_utilisateur=" + id_utilisateur;
}

$('.btnRetirerRdvAdmin').click(function (e) {
    e.preventDefault();
    var $this = $(this);

    idReservation1 = $this.data('ids');
    idReservation2 = idReservation1.split(' ');
    idReservation = idReservation2[0];
    idUtilisateur = idReservation2[1];

    $('#btnSubmitRetirerRdvAdmin').data({
        idReservation: idReservation, 
        idUtilisateur: idUtilisateur,
    });
});

$('#btnSubmitRetirerRdvAdmin').click(function (e) {
    e.preventDefault();
    var $this = $(this);
    retirerRdvAdmin($this.data("idReservation"), $this.data("idUtilisateur"));
});

$('#btnCloseRetirerRdvAdmin').click(function (e) {
    e.preventDefault();
    $('#btnRetirerRdvAdmin').modal('hide');
});

// Commentaire des rendez-vous 
function voir_commentaire(commentaireClient, commentaireAdmins, reservation_id)
{
    $('#btnCommentaireClientEtAdmin').modal('show');
    $('#commentaireClient').val(commentaireClient);
    $('#commentaireAdmins').val(commentaireAdmins);
    $('#reservation_id').val(reservation_id);
}

$(document).ready(function(){

    // Liste dynamique pour les horaires
    $('#joursReservations').change(function(){
        var joursReservations_id = document.getElementById('joursReservations').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {joursReservationsID:joursReservations_id},
            success:function(data){
                $('#horairesReservations').html(data);
                document.getElementById('horairesReservations').disabled = false;
            },
        })
    })

    // Liste dynamique pour les horaires à supprimer
    $('#joursReservationsASupprimer').change(function(){
        var joursReservationsASupprimer_id = document.getElementById('joursReservationsASupprimer').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {joursReservationsASupprimerID:joursReservationsASupprimer_id},
            success:function(data){
                $('#horairesReservationsASupprimer').html(data);
                document.getElementById('horairesReservationsASupprimer').disabled = false;
            },
        })
    })

    // Liste dynamique pour les horaires de tous les réservations
    $('#joursReservations2').change(function(){
        var joursReservations_id2 = document.getElementById('joursReservations2').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {joursReservationsID2:joursReservations_id2},
            success:function(data){
                $('#horairesReservations2').html(data);
                document.getElementById('horairesReservations2').disabled = false;
            },
        })
    })

    // Liste dynamique pour la précision du type de réservation
    $('#horairesReservations2').change(function(){
        var horairesReservations2 = document.getElementById('horairesReservations2').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {horairesReservations2ID:horairesReservations2},
            success:function(data){
                $('#typePrecision').html(data);
                document.getElementById('typePrecision').disabled = false;
            },
        })
    })
    
    // Liste des clients, si sélectionner, champ d'identité disparaît
    $('#id_utilisateur').change(function(){
        document.getElementById('commentaireClientInput').removeAttribute('required');
        document.getElementById('commentaireAdminsDiv').style.display = 'none';
    })
    // Liste dynamique pour les informations du client
    $('#id_utilisateur_reservation').change(function(){
        var id_utilisateur = document.getElementById('id_utilisateur_reservation').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {id_utilisateur_infosID0:id_utilisateur},
            success:function(data){
                $('#InformationsClient0').attr('placeholder', data);
            },
        })
    })
    // Liste dynamique pour les informations du client
    $('#id_utilisateur_reservation').change(function(){
        var id_utilisateur = document.getElementById('id_utilisateur_reservation').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {id_utilisateur_infosID1:id_utilisateur},
            success:function(data){
                $('#InformationsClient1').attr('placeholder', data);
            },
        })
    })
    // Liste dynamique pour les informations du client
    $('#id_utilisateur_reservation').change(function(){
        var id_utilisateur = document.getElementById('id_utilisateur_reservation').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {id_utilisateur_infosID2:id_utilisateur},
            success:function(data){
                $('#InformationsClient2').attr('placeholder', data);
            },
        })
    })
    // Liste dynamique pour les informations du client
    $('#id_utilisateur_reservation').change(function(){
        var id_utilisateur = document.getElementById('id_utilisateur_reservation').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {id_utilisateur_infosID3:id_utilisateur},
            success:function(data){
                $('#InformationsClient3').attr('placeholder', data);
            },
        })
    })

    // Liste dynamique pour les réservations du client
    $('#id_utilisateur_reservation').change(function(){
        var id_utilisateur = document.getElementById('id_utilisateur_reservation').value;

        $.ajax({
            url: '../../controlleur/controlleurTableaudebordAdmin.php',
            method: 'POST',
            data: {id_utilisateur_resaID:id_utilisateur},
            success:function(data){
                $('#reservationsClient').html(data);
                document.getElementById('reservationsClient').disabled = false;
            },
        })
    })

    // Modale "Ajouter une réservation"
    // Liste des types si sélectionner, bouton Enregistrer perd son disabled
    $('#typeMessageOuMachine').change(function(){
        document.getElementById('ajoutRendezvousModal').disabled = false;
    })

    // Modale "Prendre une réservation"
    // Liste des types si sélectionner, bouton Ajouter perd son disabled
    $('#typePrecision').change(function(){
        document.getElementById('PrendreReservationModal').disabled = false;
    })

    // Modale "Supprimer une réservation"
    // Liste des horaires si sélectionner, bouton Supprimer perd son disabled
    $('#horairesReservationsASupprimer').change(function(){
        document.getElementById('supprimerRendezvousModal').disabled = false;
    })

    // Modale "Réservations et infos client"
    // Liste des clients si sélectionner, bouton Enregistrer perd son disabled
    $('#reservationsClient').change(function(){
        document.getElementById('btnSubmitRetirerRdvAdmin2').disabled = false;
    })

    // Modale "Bloquer / débloquer un client"
    // Liste des clients si sélectionner, champ Bloquer / débloquer, bouton perd son disabled
    $('#id_utilisateur_a_boquer').change(function(){
        document.getElementById('bloquerUtilisateur').disabled = false;
    })
})

// Gestion des spinners
$('#ajoutRendezvousModal').click(function(){
    $('#ajoutRendezvousModal i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#PrendreReservationModal').click(function(){
    $('#PrendreReservationModal i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#supprimerRendezvousModal').click(function(){
    $('#supprimerRendezvousModal i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#bloquerUtilisateur').click(function(){
    $('#bloquerUtilisateur i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#btnSubmitRetirerRdvAdmin').click(function(){
    $('#btnSubmitRetirerRdvAdmin i')
        .removeClass('fas fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});

$('#btnSubmitRetirerRdvAdmin2').click(function(){
    $('#btnSubmitRetirerRdvAdmin2 i')
        .removeClass('fas fa-arrow-left')
        .addClass('fa fa-spinner')
        .addClass('fa-spin');
});

$('#btnSubmitEditerCommentaireAdmins').click(function(){
    $('#btnSubmitEditerCommentaireAdmins i')
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