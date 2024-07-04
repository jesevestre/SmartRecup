// Ouverture de la modale Offre de bienvenue
// if(document.cookie.indexOf('PHPSESSID') == -1) {
//     setTimeout(() => {
//         ouvrirModaleOffre();
//     }, 2000);
// }
// function ouvrirModaleOffre() {
//     Swal.fire({
//         title: 'OFFRE DE BIENVENUE',
//         text: 'Votre première séance à seulement 14,90€, alors n\'hésitez plus !',
//         imageWidth: 300,
//         imageHeight: 400,
//         confirmButtonText: 'Fermer',
//         showClass: {
//             popup: 'slow-show',
//             confirmButton: 'custom-confirm-button',
//         },
//     });
// }

/* Effet visuel en arrivant sur la page */ 
document.addEventListener('DOMContentLoaded', function() {
    const slogan = document.getElementById('slogan-div');
    slogan.classList.add('move-in');
});

/* Modale pour ouvrir le code Snapchat */
function ouvrirModaleSnapchat() {
    Swal.fire({
        //   title: 'Titre de l\'alerte',
        text: 'Scannez-moi ou copiez maxx_thiel pour m\'ajouter',
        imageUrl: 'contenu/images/QRCode_Snapchat.png',
        imageWidth: 300,
        imageHeight: 400,
        imageAlt: 'Snapcode de Maxime Thiel',
        confirmButtonText: 'Fermer',
        showClass: {
            popup: 'slow-show',
            confirmButton: 'custom-confirm-button'
        },
    });
}