// Numéro de téléphone de la réservation
function voir_infos_client(telephone, identite, id)
{
    $('#btnVoirTelephone').modal('show');
    $('#telephone').val(telephone);
    $('#identite').val(identite);
    $('#id').val(id);
}

$('.prendreReservation').click(function (e) {
    e.preventDefault();
    var $this = $(this);

    ids = $this.data('ids');
    ids2 = ids.split(' ');
    id = ids2[0];
    time = ids2[1];

    time = time.substr(0,5);
    time = time.replace(':', 'h');

    $('#id_reservation').val(id);
    $('#time').text(time);
});

$('#btnCloseSenregistrer').click(function (e) {
    e.preventDefault();
    $('#prendreReservation').modal('hide');
});

$('#libererBoutonEnregistrerSuppr').change(function(){
    document.getElementById('supprReservationModale').disabled = false;
})
$('#supprReservationModale').click(function(){
    $('#supprReservationModale i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});

$('#retirerBoutonEnregistrerSuppr').change(function(){
    document.getElementById('retirerReservationModale').disabled = false;
})
$('#retirerReservationModale').click(function(){
    $('#retirerReservationModale i')
        .removeClass('fa-save')
        .addClass('fa-spinner')
        .addClass('fa-spin');
});