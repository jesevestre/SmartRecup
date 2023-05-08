// Gestion des mots de passe
function verifMdp() {
    var mdp = document.getElementById("mdp").value;
    var mdpRepete = document.getElementById("mdpRepete").value;
    var mdpRepeteLength = mdpRepete.length;
    var texte = "";

    // Si le mdp de vérification n'est pas correct
    if (mdp != mdpRepete) {
        texte = ('<div id="divMessage" style="display: block" class="alert alert-warning text-center small"><i class="fa fa-exclamation-triangle"></i> Le mot de passe de vérification n\'est pas correct.</div>');

        document.getElementById('texteErreur').innerHTML = texte;
        inscription.classList.add("disabled");

        telephone.classList.remove("pb-5");
        telephone.classList.add("pb-1");

    // Si le mdp de vérification  est trop court
    } else if (mdpRepeteLength < 5) {
        texte = ('<div id="divMessage" style="display: block" class="alert alert-warning text-center small"><i class="fa fa-exclamation-triangle"></i> Le mot de passe est trop court.</div>');

        document.getElementById('texteErreur').innerHTML = texte;
        inscription.classList.add("disabled");

        telephone.classList.remove("pb-5");
        telephone.classList.add("pb-1");

    // Si le mdp de vérification est correct on libère me bouton pour s'inscrire
    } else {
        document.getElementById('texteErreur').innerHTML = texte;
        inscription.classList.remove("disabled");

        telephone.classList.remove("pb-1");
        telephone.classList.add("pb-5");
    }
}