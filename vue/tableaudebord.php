<?php include "entetedepage.php"; ?>

<style>
    body {
        background: #BBB;
    }
</style>

<?php
$date_aujourdhui = date("Y-m-d");
$dateHeure_aujourdhui = date("Y-m-dTh:00");
$dateHeure_aujourdhui1 = substr($dateHeure_aujourdhui, 0, 10);
$dateHeure_aujourdhui2 = substr($dateHeure_aujourdhui, 13, 6);
$dateHeure_aujourdhui = $dateHeure_aujourdhui1 . $dateHeure_aujourdhui2;

// Liste des horaires disponibles pour les séances de ventouse
$sql = "SELECT id AS id_reservation, date AS date_reservation 
        FROM Reservations 
        WHERE id_utilisateur IS NULL AND id_type = 1 AND date >= ?";    
$req = $pdo->prepare($sql);
$req->execute(array($date_aujourdhui));
$horairesVentouses = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des horaires disponibles pour les séances de massage
$sql = "SELECT id AS id_reservation, date AS date_reservation 
        FROM Reservations 
        WHERE id_utilisateur IS NULL AND id_type = 2 AND date >= ?";    
$req = $pdo->prepare($sql);
$req->execute(array($date_aujourdhui));
$horairesMassages = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des horaires disponibles pour les séances de ventouse et massage
$sql = "SELECT id AS id_reservation, date AS date_reservation 
        FROM Reservations 
        WHERE id_utilisateur IS NULL AND id_type = 3 AND date >= ?";    
$req = $pdo->prepare($sql);
$req->execute(array($date_aujourdhui));
$horairesVentousesMassages = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des horaires non réservés suppriable par l'administrateur
$sql = "SELECT Reservations.id AS id_reservation, Reservations.date AS date_reservation, Reservation_type.libelle AS type_reservation 
        FROM Reservations 
        INNER JOIN Reservation_type ON Reservations.id_type = Reservation_type.id 
        WHERE id_utilisateur IS NULL AND date >=  ?";    
$req = $pdo->prepare($sql);
$req->execute(array($date_aujourdhui));
$horairesSuppression = $req->fetchAll(PDO::FETCH_OBJ);

// Récupération des informations personnelles de l'utilisateur
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $sql = "SELECT * FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
    $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);
}

// Récupération des reservations de l'utilisateur
if($utilisateur[0]->administrateur != 1) {
    $email = $_SESSION["email"];
    $sql = "SELECT Reservations.id AS reservation_id, res_etat.libelle AS libelle_etat, res_type.libelle AS libelle_type, duree_moyenne, date, commentaire
            FROM Reservations 
            INNER JOIN Utilisateurs ON Reservations.id_utilisateur = Utilisateurs.id
            INNER JOIN Reservation_type AS res_type ON Reservations.id_type = res_type.id
            INNER JOIN Reservation_etat AS res_etat ON Reservations.id_etat = res_etat.id
            WHERE email = ?
            ORDER BY date DESC";    
    $req = $pdo->prepare($sql);
    $req->execute(array($email));
    $reservationsUtilisateur = $req->fetchAll(PDO::FETCH_OBJ);
}

// Récupération des reservations pour l'administrateur
if($utilisateur[0]->administrateur == 1) {
    $sql = "SELECT Reservations.id AS reservation_id, res_etat.libelle AS libelle_etat, res_type.libelle AS libelle_type, duree_moyenne, date, commentaire, prenom, nom, email, telephone, genre
            FROM Reservations 
            INNER JOIN Utilisateurs ON Reservations.id_utilisateur = Utilisateurs.id
            INNER JOIN Reservation_type AS res_type ON Reservations.id_type = res_type.id
            INNER JOIN Reservation_etat AS res_etat ON Reservations.id_etat = res_etat.id
            WHERE date >= ?
            ORDER BY date ASC";    
    $req = $pdo->prepare($sql);
    $req->execute(array($date_aujourdhui));
    $reservationsAdministrateur = $req->fetchAll(PDO::FETCH_OBJ);
}
?>

<?php
// Si c'est une vrai connexion et que la session est toujours active
if($_SESSION["email"]) {
?>
<div class="container">
    <div class="row justify-content-around d-flex justify-content-center cadrage4">

        <!-- Gestion des messages et erreurs -->
        <?php
        // La div des messages à afficher est de base invisible
        $display = "display: none";
        if (isset($_GET["error"]) || isset($_GET["success"])) {
            $error = $_GET["error"];
            $success = $_GET["success"];
            // La div des messages à afficher est visible
            $display = "display: block";

            if ($error == "error") {
                $message = "L'enregistrement n'a pas fonctionné, veuillez réessayer";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if ($error == "error2") {
                $message = "Assurez-vous bien que tous les champs obligatoires soient renseignés";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if ($error == "error3") {
                $message = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <i><b>sevestre.jb@gmail.com</b></i>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-danger";
            }  else if ($error == "error4") {
                $message = "";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-danger"; 
            } else if ($error == "error5") {
                    $message = "";
                    $icone = "fa fa-exclamation-triangle";
                    $color = "alert-danger";
            } else if ($success == "success" && $utilisateur[0]->administrateur != 1) {
                $message = "Bienvenue " . $utilisateur[0]->prenom . " ! A partir de cette interface, vous pouvez gérer vos rendez-vous";
                $icone = "fas fa-hand-holding-medical";
                $color = "alert-success";
            } else if ($success == "success" && $utilisateur[0]->administrateur == 1) {
                $message = "Bienvenue " . $utilisateur[0]->prenom . " ! A partir de cette interface, vous pouvez gérer les rendez-vous des utilisateurs et prendre note de ceux réservés";
                $icone = "fas fa-hand-holding-medical";
                $color = "alert-success";
            } else if ($success == "success2") {
                $message = "Votre profil a bien été mis à jour";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if ($success == "success3") {
                $message = "Le rendez-vous a été pri. Vous pouvez la visualiser dans la liste de vos réservations";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if ($success == "success4") {
                $message = "Le rendez-vous a été ajouté. Il pourra être réservé par les utilisateurs";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if ($success == "success5") {
                $message = "Le rendez-vous a été supprimer. Il n'est plus disponible à la réservation par les utilisateurs";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if ($success == "success6") {
                $message = "Le rendez-vous a bien été retirer";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if ($success == "success7") {
                $message = "Le commentaire du rendez-vous a été mis à jour";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            }
        }
        ?>
        <div class="col-11 cadrageMessage">
            <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
                <i class="<?= $icone ?>"></i> <b><?= $message; ?></b>
            </div>
            <div id="divMessage2"></div>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <div class="col-12 col-lg-4 mt-3 mb-3 cadrage2">
            <div class="col-12 d-grid mx-auto pt-3">
                <a href="#" class="btn btn-info col-11 mx-auto"><?php if ($utilisateur[0]->administrateur != 1) { ?><b>Prendre un rendez-vous pour :</b><?php } else { ?><b>Gestion des rendez-vous :</b><?php } ?></a>
            </div>

            <?php if ($utilisateur[0]->administrateur != 1) { ?>
            <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-success col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousVentouse" data-bs-backdrop="static"><i class="fa-sharp fa-solid fa-calendar-check"></i> Les ventouses</a>
            </div>

            <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-success col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousMassage" data-bs-backdrop="static"><i class="fa-sharp fa-solid fa-calendar-check"></i> Les massages</a>
            </div>

            <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-success col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousVentouseMassage" data-bs-backdrop="static"><i class="fa-sharp fa-solid fa-calendar-check"></i> Les ventouses et les massages</a>
            </div>
            <?php } else { ?>
                <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-success col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousAjout" data-bs-backdrop="static"><i class="fa-sharp fa-solid fa-calendar-check"></i> Ajouter un rendez-vous</a>
            </div>
            <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-danger col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousSupprimer" data-bs-backdrop="static"><i class="fa fa-times"></i> Supprimer un rdv non réservé</a>
            </div>
            <?php } ?>

            <div class="col-12 d-grid mx-auto pt-3 pb-3">
                <a class="btn btn-warning col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#informationModal" data-bs-backdrop="static"><i class="fas fa-clipboard-check"></i> Informations sur le rendez-vous</a>
            </div>

            <div class="col-11 d-grid mx-auto bg-light rounded pt-3">
                <div style=" margin-left: auto; margin-right: auto;">
                    <p><i class="fa-solid fa-yin-yang"></i> <?php if ($utilisateur[0]->genre == 1) { echo "Monsieur"; } else { echo "Madame"; } ?></p>
                    <p><i class="far fa-user-circle"></i> <?= $utilisateur[0]->prenom . " " . $utilisateur[0]->nom ?></p>
                    <p><i class="far fa-at"></i> <?= $utilisateur[0]->email ?></p>
                    <p><i class="fas fa-phone"></i> <?= $utilisateur[0]->telephone ?></p>
                </div>
            </div>

            <div class="col-12 d-grid mx-auto pt-3">
                <a class="btn btn-primary col-11 mx-auto" data-bs-toggle="modal" data-bs-target="#editProfilModal" data-bs-backdrop="static"><i class="fa fa-edit"></i> Modifier mon profil</a>
            </div>

            <div class="col-12 d-grid mx-auto pb-3 pt-3">
                <a href="deconnexion.php" type="button" class="btn btn-danger col-11 mx-auto"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>

        </div>

        <div class="col-12 col-lg-7 mt-3 mb-3 cadrage2">
            <div class="pt-3 mb-3">
                <h2><?php if ($utilisateur[0]->administrateur != 1) { ?><b>Liste de vos réservations</b><?php } else { ?>Liste des rdv pris par les utilisateurs<?php } ?></h2>
            </div>

            <?php if ($utilisateur[0]->administrateur != 1) { ?>
                <div class="row">
                    <?php foreach($reservationsUtilisateur AS $reservationUti):

                        $dateColor = substr($reservationUti->date, 0, 10);
                        if ($dateColor == $date_aujourdhui) {
                            $themeCard = "text-white bg-success";
                        } elseif($dateColor < $date_aujourdhui) {
                            $themeCard = "text-white bg-secondary";
                        } else {
                            $themeCard = "bg-light";
                        }

                        if ($dateColor == $date_aujourdhui) {
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "Aujourd'hui à " . $heure . "h" . $minute;
                        } else {
                            $annee = substr($reservationUti->date, 0, 4);
                            $mois = substr($reservationUti->date, 5, 2);
                            $jour = substr($reservationUti->date, 8, 2);
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "Le " . $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            $date2 = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                        }

                        $heure = substr($reservationUti->duree_moyenne, 1, 1);
                        $minute = substr($reservationUti->duree_moyenne, 3, 2);
                        $temps = $heure . "h" . $minute;
                        ?>

                        <div class="col-12 mb-3">
                            <div class="card <?= $themeCard ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <b><?= $reservationUti->libelle_type ?></b>
                                        </div>
                                        <div class="col-5">
                                            <?= $date ?>
                                        </div>
                                        <div class="col-2">
                                            <?= $temps ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="modal-footer col-10 mt-3">
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <?php if ($dateColor >= $date_aujourdhui) { ?>
                                                    <a class="btn btn-primary mx-auto w-100 btnEditerCommentaire" onclick="javascript:editer_commentaire('<?= $reservationUti->commentaire ?>', '<?= $reservationUti->reservation_id ?>');"><i class="fa fa-comment"></i></a>
                                                <?php } ?>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <?php if ($dateColor > $date_aujourdhui) { ?>
                                                    <a class="btn btn-warning mx-auto w-100 btnRetirerRdv" data-idrdv="<?= $reservationUti->reservation_id ?>" data-date2="<?php $date2 ?>" data-bs-toggle="modal" data-bs-target="#btnRetirerRdv" href="#" data-bs-backdrop="static"><i class="fa fa-times"></i></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php } else { ?>
                <div class="row">
                    <?php foreach($reservationsAdministrateur AS $reservationAdministrateur):

                        $dateColor = substr($reservationAdministrateur->date, 0, 10);
                        if ($dateColor == $date_aujourdhui) {
                            $themeCard = "text-white bg-success";
                        } else {
                            $themeCard = "bg-light";
                        }

                        if ($reservationAdministrateur->genre == 1) {
                            $genre = "Monsieur";
                        } else {
                            $genre = "Madame";
                        }

                        if ($dateColor == $date_aujourdhui) {
                            $heure = substr($reservationAdministrateur->date, 11, 2);
                            $minute = substr($reservationAdministrateur->date, 14, 2);
                            $date = "Aujourd'hui à " . $heure . "h" . $minute;
                        } else {
                            $annee = substr($reservationAdministrateur->date, 0, 4);
                            $mois = substr($reservationAdministrateur->date, 5, 2);
                            $jour = substr($reservationAdministrateur->date, 8, 2);
                            $heure = substr($reservationAdministrateur->date, 11, 2);
                            $minute = substr($reservationAdministrateur->date, 14, 2);
                            $date = "Le " . $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            $date2 = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                        }
                        
                        $heure = substr($reservationAdministrateur->duree_moyenne, 1, 1);
                        $minute = substr($reservationAdministrateur->duree_moyenne, 3, 2);
                        $temps = $heure . "h" . $minute;
                    ?>
                        <div class="col-12 mb-3">
                            <div class="card <?= $themeCard ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="card-title"><?= $genre . " " . $reservationAdministrateur->prenom . " " . $reservationAdministrateur->nom . " - " . $reservationAdministrateur->telephone . " - " . $reservationAdministrateur->email ?></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">
                                            <b><?= $reservationAdministrateur->libelle_type ?></b>
                                        </div>
                                        <div class="col-5">
                                            <?= $date ?>
                                        </div>
                                        <div class="col-2">
                                            <?= $temps ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="modal-footer col-10 mt-3">
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <?php if ($reservationAdministrateur->commentaire != NULL || $reservationAdministrateur->commentaire != "") { ?>
                                                    <a class="btn btn-primary mx-auto w-100 btnVoirCommentaire" onclick="javascript:voir_commentaire('<?= $reservationAdministrateur->commentaire ?>')"><i class="fas fa-comment"></i></a>
                                                <?php } ?>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <a class="btn btn-warning mx-auto w-100 btnRetirerRdv" data-idrdv="<?= $reservationAdministrateur->reservation_id ?>" data-date2="<?php $date2 ?>" data-bs-toggle="modal" data-bs-target="#btnRetirerRdv" href="#" data-bs-backdrop="static"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            
            <?php } ?>
        </div>

    </div>
</div>

<?php
// Si ce n'est pas une vrai connexion, retour à l'accueil
} else {
?>
<div class="container">
    <div class="row justify-content-around d-flex justify-content-center cadrage4">
        <div class="col-12 d-grid mx-auto pb-3 pt-3">
            <div class="alert alert-warning text-center small">
                <b>Votre session a expirée, veuillez vous reconnecter</b>
            </div>
        </div>
        <div class="col-12 d-grid mx-auto pb-3">
            <a href="connexion.php" type="button" class="btn btn-primary col-11 mx-auto"><i class="fas fa-sign-out-alt"></i> Connexion</a>
        </div>
    </div>
</div>
<?php
}
?>

<!-- Modale pour editer le commentaire -->
<div class="modal fade" id="btnEditerCommentaire" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-list"></i> Gérer Le commentaire de ce rendez-vous</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Commentaire : <input class="form-control" id="voirCommentaireUti" disabled>              
                    </div>
                    <div class="form-group pt-3">
                        Remplacer le commentaire : <input type="text" name="commentaire" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="editCommentaireModal">
                    <input type="hidden" name="id_reservation" id="reservation_id">
                    <div class="col-5">
                        <a href="#" id="btnCloseRetirerRdv" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-success btn-sm text-white" style="width: 90%;" id="btnSubmitEditerCommentaire" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale pour voir le commentaire -->
<div class="modal fade" id="btnVoirCommentaire" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-list"></i> Le commentaire de ce rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Commentaire : <input class="form-control" id="voirCommentaire" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Fermer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour rétirer un rendez-vous -->
<div class="modal fade" id="btnRetirerRdv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- <form method="post" action="#" id="formDeleteCommentaireMairie"> -->
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-times"></i> Retirer ce rendez-vous</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Vous êtes sur le point de retirer ce rendez-vous. Il sera libéré pour un autre utilisateur.
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" id="btnCloseRetirerRdv" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-warning btn-sm text-white" style="width: 90%;" id="btnSubmitRetirerRdv" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Retirer <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        <!-- </form> -->
    </div>
</div>

<!-- Modale de prise de rendez-vous pour les ventouses (type = 1) -->
<div class="modal fade" id="rendezvousVentouse" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre un rendez-vous pour les ventouses</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="maire">Horaires disponibles :</label>
                        <select class="form-control" name="id_reservation" id="id_reservation">
                            <option selected disabled>Sélectionner un horaire à réserver</option>
                            <?php
                            foreach ($horairesVentouses as $horairesVentouse) {
                                $annee = substr($horairesVentouse->date_reservation, 0, 4);
                                $mois = substr($horairesVentouse->date_reservation, 5, 2);
                                $jour = substr($horairesVentouse->date_reservation, 8, 2);
                                $heure = substr($horairesVentouse->date_reservation, 11, 2);
                                $minute = substr($horairesVentouse->date_reservation, 14, 2);
                                $date = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            ?>
                                <option value="<?= $horairesVentouse->id_reservation; ?>"><?= $date; ?></option>
                            <?php
                            } 
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de prise de rendez-vous pour les massages (type = 2) -->
<div class="modal fade" id="rendezvousMassage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre un rendez-vous pour les massages</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="maire">Horaires disponibles :</label>
                        <select class="form-control" name="id_reservation" id="id_reservation">
                            <option selected disabled>Sélectionner un horaire à réserver</option>
                            <?php
                            foreach ($horairesMassages as $horairesMassage) {
                                $annee = substr($horairesMassage->date_reservation, 0, 4);
                                $mois = substr($horairesMassage->date_reservation, 5, 2);
                                $jour = substr($horairesMassage->date_reservation, 8, 2);
                                $heure = substr($horairesMassage->date_reservation, 11, 2);
                                $minute = substr($horairesMassage->date_reservation, 14, 2);
                                $date = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            ?>
                                <option value="<?= $horairesMassage->id_reservation; ?>"><?= $date; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de prise de rendez-vous pour les ventouses et massages (type = 3) -->
<div class="modal fade" id="rendezvousVentouseMassage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre un rdv pour les ventouses et massages</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="maire">Horaires disponibles :</label>
                        <select class="form-control" name="id_reservation" id="id_reservation">
                            <option selected disabled>Sélectionner un horaire à réserver</option>
                            <?php
                            foreach ($horairesVentousesMassages as $horairesVentouseMassage) {
                                $annee = substr($horairesVentouseMassage->date_reservation, 0, 4);
                                $mois = substr($horairesVentouseMassage->date_reservation, 5, 2);
                                $jour = substr($horairesVentouseMassage->date_reservation, 8, 2);
                                $heure = substr($horairesVentouseMassage->date_reservation, 11, 2);
                                $minute = substr($horairesVentouseMassage->date_reservation, 14, 2);
                                $date = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            ?>
                                <option value="<?= $horairesVentouseMassage->id_reservation; ?>"><?= $date; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'ajout de rendez-vous -->
<div class="modal fade" id="rendezvousAjout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Ajouter un rendez-vous</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group pb-3">
                            <label for="type">Type</label>
                            <div>
                                <label class="legendeVentouse" for="1">Ventouse</label>
                                <input type="radio" name="type" value="1" checked>
                                <label class="legendeMassage" for="2">Massage</label>
                                <input type="radio" name="type" value="2">
                                <label class="legendeVentouseMassage" for="3">Ventouse et massage</label>
                                <input type="radio" name="type" value="3">
                            </div>
                            <label for="dateTime" class="mt-3">Horaire</label>
                            <div class="cursorGrab">
                                <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" value="<?= $dateHeure_aujourdhui ?>" min="<?= $dateHeure_aujourdhui ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="ajoutRendezvousModal">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de suppression d'un rendez-vous tout type confondu -->
<div class="modal fade" id="rendezvousSupprimer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-cross"></i> Supprimer rdv de façon définitive</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="maire">Horaires non réservés :</label>
                        <select class="form-control" name="id_reservation" id="id_reservation">
                            <option selected disabled>Sélectionner un horaire à supprimer</option>
                            <?php
                            foreach ($horairesSuppression as $horaireSuppression) {
                                $annee = substr($horaireSuppression->date_reservation, 0, 4);
                                $mois = substr($horaireSuppression->date_reservation, 5, 2);
                                $jour = substr($horaireSuppression->date_reservation, 8, 2);
                                $heure = substr($horaireSuppression->date_reservation, 11, 2);
                                $minute = substr($horaireSuppression->date_reservation, 14, 2);
                                $date = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            ?>
                                <option value="<?= $horaireSuppression->id_reservation ?>"><?= $horaireSuppression->type_reservation . " le " . $date; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="supprimerRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Supprimer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'information -->
<div class="modal fade" id="informationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-clipboard-check"></i> Information rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>- Quartier à stationnement gratuit, l'adresse du rendez-vous est le 42 rue Edward Doyennette, Lille 59 000.</p>
                <p>- Une séance de ventouse dure en moyenne 1h. Tarif classique: 40€</p>
                <p>- Une séance de massage musculaire dure en moyenne 1h. Tarif classique: 40€</p>
                <p>- Une séance de ventouse plus massage dure en moyenne 1h30. Tarif classique: 60€</p>
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Fermer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale d'édition du profil -->
<div class="modal fade" id="editProfilModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="../controlleur/controlleurTableaudebord.php">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Modifier le profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group pb-2">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" value="<?= $utilisateur[0]->email ?>" disabled>
                    </div>
                    <div class="form-group pb-2">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" name="prenom" id="prenom" onChange="this.value=premierCaractereMaj(this.value)" value="<?= $utilisateur[0]->prenom ?>" required>
                    </div>
                    <div class="form-group pb-2">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control uppercase" name="nom" id="nom" onkeyup="this.value=this.value.toUpperCase()" value="<?= $utilisateur[0]->nom ?>" required>
                    </div>
                    <div class="form-group pb-3">
                        <label for="genre">Genre</label>
                        <div>
                            <label class="legendegenreModaleFemme" for="0">Femme</label>
                            <input type="radio" name="genre" value="0" <?php if ($utilisateur[0]->genre == 0) { ?> checked <?php } ?>>
                            <label class="legendegenreModaleHomme" for="1">Homme</label>
                            <input type="radio" name="genre" value="1" <?php if ($utilisateur[0]->genre != "0") { ?> checked <?php } ?>>
                        </div>
                    </div>
                    <div class="form-group pb-2">
                        <label for="telephone">Téléphone</label>
                        <input type="text" class="form-control" name="telephone" id="telephone" value="<?= $utilisateur[0]->telephone ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-secondary btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="editProfilModal">
                        <input type="hidden" name="email" value="<?= $utilisateur[0]->email ?>">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="btnEditProfil" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<script src="../contenu/js/tableaudebord.js"></script>