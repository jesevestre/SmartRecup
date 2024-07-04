// Gestion des spinners
$('#btnRetour').click(function(){
    $('#btnRetour i')
        .removeClass('fa-sharp fa-solid fa-arrow-left')
        .addClass('fa fa-spinner fa-spin')
});
$('#btnInscription').click(function(){
    $('#btnInscription i')
        .removeClass('fa-sharp fa-solid fa-arrow-right')
        .addClass('fa fa-spinner fa-spin')
});