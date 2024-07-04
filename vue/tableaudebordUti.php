<?php session_start(); ?>
<?php include("../contenu/connexion/BDDIntersection.php"); ?>
<?php include "entetedepage.php"; ?>

<?php
$date_aujourdhui = date("Y-m-d");
$dateHeure_aujourdhui = date("Y-m-dTh:00");
$dateHeure_aujourdhui1 = substr($dateHeure_aujourdhui, 0, 10);
$dateHeure_aujourdhui2 = substr($dateHeure_aujourdhui, 13, 6);
$dateHeure_aujourdhui = $dateHeure_aujourdhui1 . $dateHeure_aujourdhui2;

$date_demain = date("Y-m-d", strtotime("+1 day"));
$date_demain_prise_rdv_avant_19h = date("Y-m-d 18:00:00", strtotime("+1 day"));

$date_apres_demain = date("Y-m-d", strtotime("+2 day"));
$date_apres_apres_demain = date("Y-m-d", strtotime("+3 day"));

// Liste des types de massage disponibles
$sql = "SELECT id AS id_type_massage, libelle 
        FROM Types_massage 
        WHERE id BETWEEN 1 AND 4";    
$req = $pdo->prepare($sql);
$req->execute();
$typesMassage = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des types de machine disponibles
$sql = "SELECT id AS id_type_machine, libelle 
        FROM Types_machine 
        WHERE id BETWEEN 1 AND 2";    
$req = $pdo->prepare($sql);
$req->execute();
$typesMachine = $req->fetchAll(PDO::FETCH_OBJ);

// Paramétrages
$sql = "SELECT *
        FROM Parametrages"; 
$req = $pdo->prepare($sql);
$req->execute();
$parametrages = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours disponibles pour les séances de massage & ventouses
$sql = "SELECT id AS id_reservation, DATE_FORMAT(date, '%Y-%m-%d') AS date_reservation 
        FROM Reservations 
        WHERE id_utilisateur IS NULL AND id_type = 1 AND date > ?
        GROUP BY date_reservation";  
$req = $pdo->prepare($sql);
$req->execute(array($date_demain_prise_rdv_avant_19h));
$joursMassage = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours disponibles pour les séances de machine
$sql = "SELECT id AS id_reservation, DATE_FORMAT(date, '%Y-%m-%d') AS date_reservation 
        FROM Reservations 
        WHERE id_utilisateur IS NULL AND id_type = 2 AND date > ?
        GROUP BY date_reservation";
$req = $pdo->prepare($sql);
$req->execute(array($date_demain_prise_rdv_avant_19h));
$joursMachine = $req->fetchAll(PDO::FETCH_OBJ);

// Récupération des informations personnelles de le client
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $sql = "SELECT * FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
    $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);
}
//echo "id : " . $utilisateur[0]->id;
// Si le champ mdpTemp est plein, on ouvre la modale "Changer mon mot de passe"
if(!empty($utilisateur[0]->mdp_temp)) {
?>
    <script>
    $(document).ready(function() {
        var modal = new bootstrap.Modal(document.getElementById('editPwdModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    });
</script>
<?php
};

// Récupération des reservations pour le client
$bouton = $_GET["bouton"];
if($bouton == "passees") {
    $option = "DATE(date) < DATE(NOW())";
    $option2 = "DESC";
} else {
    $option = "DATE(date) >= DATE(NOW())";
    $option2 = "ASC";
}

$sql = "SELECT Reservations.id AS reservation_id, res_type.libelle AS libelle_type, date, commentaireClient, commentaireAdmins, Reservations.id_utilisateur AS utilisateur_id, type_machine.libelle AS libelle_type_machine, type_massage.libelle AS libelle_type_massage
        FROM Reservations
        JOIN Reservation_type AS res_type ON Reservations.id_type = res_type.id
        LEFT JOIN Utilisateurs AS utilisateur ON Reservations.id = utilisateur.id
        LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
        LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
        WHERE Reservations.id_utilisateur = ? AND $option
        ORDER BY date $option2";    
$req = $pdo->prepare($sql);
$req->execute(array($utilisateur[0]->id));
$reservationsUtilisateur = $req->fetchAll(PDO::FETCH_OBJ);

// Si c'est une vrai connexion et que la session est toujours active
if($_SESSION["email"]) {
?>
<div class="container">
    <div class="row justify-content-around d-flex justify-content-center">

        <!-- Début de gestion des messages et erreurs -->
        <?php
        // La div des messages à afficher est de base invisible
        $display = "display: none";
        if(isset($_GET["error"]) || isset($_GET["success"]) || isset($_GET["bouton"])) {
            $error = $_GET["error"];
            $success = $_GET["success"];
            // La div des messages à afficher est visible
            $display = "display: block";

            if($error == "error") {
                $message = "<b>L'enregistrement n'a pas fonctionné, veuillez réessayer</b>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if($error == "error2") {
                $message = "<b>Assurez-vous bien que tous les champs obligatoires soient renseignés</b>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if($error == "error3") {
                $message = "<b>Assurez-vous bien que votre mot de passe actuel est correctement été renseigné</b>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
                
            } else if($success == "successConnexion") {
                $message = "<b>Bienvenue " . $utilisateur[0]->prenom . " " . $utilisateur[0]->nom . " !</b> A partir de cette interface, vous pouvez gérer vos réservations.";
                $icone = "fas fa-hand-holding-medical";
                $color = "alert-success";
            } else if($success == "success2") {
                $message = "<b>Votre profil a été mis à jour.</b>";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success3") {
                $message = "<b>Le rendez-vous a été réservé et un email de confirmation vous a été envoyé.</b> Vous pouvez le visualiser ci-dessous dans vos réservations.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success4") {
                $message = "<b>Le rendez-vous a été libéré</b> pour un autre client.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success5") {
                $message = "<b>Le commentaire de la réservation a été mis à jour.</b>";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success6") {
                $message = "<b>Votre mot de passe a été mis à jour.</b> Lors de votre prochaine connexion, vous devrez vous connecter avec celui-ci.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($bouton == "encours" || $bouton == "passees") {
                $message = "<b>Les filtres</b> vous permettent d'affiner vos recherches concernant vos réservations.";
                $icone = "fas fa-arrow-right";
                $color = "alert-success";
            }
        }
        ?>
        <div class="col-12 cadrageMessage">
            <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
                <i class="<?= $icone ?>"></i> <?= $message; ?>
            </div>
            <div id="divMessage2"></div>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <!-- Début menu du haut de page -->
        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#infos_perso" class="btn"><i class="fa-2x fa-solid fa-user icone-color"></i></a>
                <a href="#prendre_rdv" class="btn a_icone-color"><i class="fa-2x fa-sharp fa-solid fa-calendar-check icone-color"></i></a>
                <a href="#vos_rdv" class="btn a_icone-color"><i class="fa-2x fa-solid fa-eye icone-color"></i></a>
                <a href="deconnexion.php" class="btn a_icone-color"><i class="fa-2x fas fa-sign-out-alt icone-color"></i></a>
            </div>
        </div>
        <!-- Fin menu du haut de page -->

        <!-- Début de la gestion des informations du clients -->
        <div id="infos_perso" class="col-12 mt-3">
            <h3><i class="fa-solid fa-user"></i> Informations personnelles :</h3>

            <div class="row mt-1">
                <div class="col-12 col-sm-6 mb-2 bg-light rounded">
                    <p class="mt-3"><i class="far fa-user-circle"></i> <?= $utilisateur[0]->prenom . " " . $utilisateur[0]->nom ?></p>
                    <p><i class="far fa-at"></i> <?= $utilisateur[0]->email ?></p>
                    <p><i class="fas fa-phone"></i> <?= (!empty($utilisateur[0]->telephone) ? $utilisateur[0]->telephone : "Non renseigné") ?></p>
                </div>

                <div class="col-12 col-sm-6 d-grid mx-auto mb-2">
                    <a class="col-12 btn btn-secondary mx-auto mt-2" href="infos_rdv_faq.php?retour=tableaudebord"><i class="fa-solid fa-circle-info"></i> Informations rendez-vous et faq</a>

                    <a class="col-12 btn btn-primary mx-auto mt-2" data-bs-toggle="modal" data-bs-target="#editProfilModal" data-bs-backdrop="static"><i class="fa fa-edit"></i> Modifier mon profil</a>

                    <a class="col-12 btn btn-warning mx-auto mt-2" data-bs-toggle="modal" data-bs-target="#editPwdModal" data-bs-backdrop="static"><i class="fa-solid fa-key"></i> Changer mon mot de passe</a>

                    <a class="col-12 btn btn-success mx-auto mt-2" href="deconnexion.php" type="button" id="deconnexion"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>
        </div>
        <!-- Fin de la gestion des informations du clients -->

        <hr class="horizontale_separation mt-3 mb-3">

        <!-- Début de la gestion pour la prise de rendez-vous -->
        <div id="prendre_rdv" class="col-12">
            <h3><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre un rendez-vous :</h3>

            <div class="row mt-1">
                <div class="col-12 col-md-6 bg-light rounded pt-3 pb-3">
                    <i class="fa-solid fa-eye"></i> Pour prendre une réservation, <b>cliquez sur l'image</b> du type de prestation souhaiter.<br /><br />
                    <i class="fa-solid fa-clock"></i> Une réservation est égale à <b>une séance de 30 minutes</b>.<br /><br />
                    <i class="fas fa-lightbulb"></i> Bon à savoir, pour prendre un rendez-vous d'une heure ou <b>une autre prestation</b>, merci de prendre une autre réservation à la suite de la précédente.
                </div>

                <div class="col-12 col-md-6 d-grid mx-auto mt-3">
                    <a class="mx-auto a_titre_et_texte avec_zoom" data-bs-toggle="modal" data-bs-target="#rendezvousMassageVentouses" data-bs-backdrop="static">
                        <img class="photo-img" src="../contenu/images/prise_rdv_img_1.png" alt="Image pour prise de rdv de massage" />
                        <h4 class="titre_h4">Massages ou ventouses</h4>
                    </a>
                    <a class="mx-auto a_titre_et_texte avec_zoom mt-3" data-bs-toggle="modal" data-bs-target="#rendezvousMachine" data-bs-backdrop="static">
                        <img class="photo-img" src="../contenu/images/prise_rdv_img_2.png" alt="Image pour prise de rdv de cryo ou pressothérapie" />
                        <h4 class="titre_h4">Cryothérapie compressive ou pressothérapie</h4>
                    </a>
                </div>
            </div>
        </div>
        <!-- Fin de la gestion pour la prise de rendez-vous -->

        <hr class="horizontale_separation mt-3 mb-3">

        <!-- Début de la gestion des réservations prisent par le client -->
        <div id="vos_rdv" class="col-12">
            <h3><i class="fa-solid fa-eye"></i> Vos réservations :</h3>
            
            <div class="row pb-3 voir_border">
                <div class="col-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary col-6" onclick="window.location.href='../vue/tableaudebordUti.php?bouton=encours';">En cours</button>
                    <button class="btn btn-primary col-6" onclick="window.location.href='../vue/tableaudebordUti.php?bouton=passees';">Passées</button>
                </div>

                <?php 
                if(!empty($reservationsUtilisateur)) {
                    foreach($reservationsUtilisateur AS $reservationUti):

                        $dateColor = substr($reservationUti->date, 0, 10);
                        if($dateColor == $date_aujourdhui) {
                            $themeCard = "text-white warning";
                        }  elseif($dateColor == $date_demain) {
                            $themeCard = "text-white danger";
                        } elseif($dateColor == $date_apres_demain) {
                            $themeCard = "text-dark warning";
                        } elseif($dateColor < $date_apres_apres_demain) {
                            $themeCard = "text-dark primary";
                        } else {
                            $themeCard = "text-dark warning";
                        }

                        if($dateColor == $date_aujourdhui) {
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "<b>Aujourd'hui à " . $heure . "h" . $minute . "</b>";
                        } else if($dateColor == $date_demain) {
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "<b>Demain à " . $heure . "h" . $minute . "</b>";
                        } else if($dateColor == $date_apres_demain) {
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "<b>Après demain à " . $heure . "h" . $minute . "</b>";
                        } else {
                            $annee = substr($reservationUti->date, 0, 4);
                            $mois = substr($reservationUti->date, 5, 2);
                            $jour = substr($reservationUti->date, 8, 2);
                            $heure = substr($reservationUti->date, 11, 2);
                            $minute = substr($reservationUti->date, 14, 2);
                            $date = "Le " . $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                            $date2 = $jour . "/" . $mois . "/" . $annee . " à " . $heure . "h" . $minute;
                        }
                        ?>

                        <div class="col-12 mt-1">
                            <div class="card <?= $themeCard ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <b><?= (!empty($reservationUti->libelle_type_machine) ? $reservationUti->libelle_type_machine : $reservationUti->libelle_type_massage) ?></b>
                                        </div>
                                        <div class="col-5">
                                            <?= $date ?>
                                        </div>
                                        <div class="col-2">
                                            30min
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="modal-footer col-10 mt-3">
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <?php 
                                                if($dateColor >= $date_aujourdhui) { 
                                                    if($reservationUti->commentaireClient == NULL || $reservationUti->commentaireClient == "") { 
                                                        $iconeCommentaire = "fas fa-comment text-white";
                                                    } else {
                                                        $iconeCommentaire = "fas fa-comment";
                                                    }
                                                    $commentaireClient = str_replace("'", " ", $reservationUti->commentaireClient);
                                                ?>

                                                    <a class="btn btn-success mx-auto w-100 btnEditerCommentaireClient" onclick="javascript:editer_commentaire('<?= $commentaireClient ?>', '<?= $reservationUti->reservation_id ?>');"><i class="<?= $iconeCommentaire ?>"></i></a>
                                                <?php } ?>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-4">
                                                <?php if($dateColor > $date_demain) { ?>
                                                    <a class="btn btn-secondary mx-auto w-100 btnRetirerRdvUti" data-ids="<?= $reservationUti->reservation_id . " " . $reservationUti->utilisateur_id . " " . $parametrages[1]->actif ?>" data-bs-toggle="modal" data-bs-target="#btnRetirerRdvUti" href="#" data-bs-backdrop="static"><i class="fa fa-times"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    endforeach;
                } else {
                ?>
                    <div class="col-10 d-grid mx-auto text-center pb-3 pt-3">
                        <div class="alert alert-primary text-dark small">
                            Vous n'avez pas de réservation dans cette section.
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- Fin de la gestion des réservations prisent par le client -->

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
            <a href="connexion-inscription.php" type="button" class="btn btn-primary col-11 mx-auto"><i class="fas fa-sign-out-alt"></i> Connexion</a>
        </div>
    </div>
</div>
<?php
}
?>

<!-- Modale de prise de réservation pour les massages (type = 1) -->
<div class="modal fade" id="rendezvousMassageVentouses" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordUti.php">
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre une réservation de massage ou de ventouses</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="alert alert-warning small">
                            <i class="fa-solid fa-clock"></i> Une réservation est égale à <b>une séance de 30 minutes</b>. Pour prendre un rendez-vous d'une heure ou <b>une autre prestation</b>, merci de prendre une autre réservation à la suite de la précédente.<br />
                            <i class="fas fa-lightbulb"></i> Bon à savoir, vous avez jusqu'à <b>48 heures avant</b> le rendez-vous pour modifier votre réservation, soit l'avant veille.
                        </div>

                        <label for="maire">Type de massage :</label>
                        <select class="form-control" name="id_type_massage" required>
                            <option value="" selected disabled>Sélectionner un type</option>
                            <?php
                            foreach ($typesMassage as $typeMassage) {
                            ?>
                                <option value="<?= $typeMassage->id_type_massage; ?>"><?= $typeMassage->libelle; ?></option>
                            <?php
                            }
                            ?>
                        </select>

                        <label for="maire" class="pt-3">Jours disponibles :</label>
                        <select class="form-control" id="joursMassage" required>
                            <option value="" selected disabled>Sélectionner un jour</option>
                            <?php
                            $i = 0;
                            $jourSemaineNum2 = 0;
                            foreach ($joursMassage as $jourMassage) {
                                $annee = substr($jourMassage->date_reservation, 0, 4);
                                $mois = substr($jourMassage->date_reservation, 5, 2);
                                $jour = substr($jourMassage->date_reservation, 8, 2);
                                $dateNumerique = $jour . "/" . $mois . "/" . $annee;

                                $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
                                $jourSemaineNum = date('N', $timestamp);
                                $jourSemaine = array("", "Lundi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "Mardi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&thinsp;", "Mercredi&nbsp;&thinsp;", "Jeudi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "Vendredi&nbsp;&nbsp;", "Samedi&nbsp;&nbsp;&nbsp;&thinsp;&thinsp;", "Dimanche");
                                $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
                                list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);
                                $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
                                $date = $jourSemaine[$jourSemaineNum] . " " . $jour . " " . $moisAnnee[$moisNum] . " " . $anneeNum;

                                if($jourSemaineNum <= $jourSemaineNum2 && $i > 0) {
                                ?>
                                    <option disabled> Nouvelle semaine : </option>
                                <?php
                                }
                                $i = $i + 1;
                                ?>

                                <option value="<?= $annee . "-" . $mois . "-" . $jour; ?>"><?= $date ?></option>

                            <?php
                            $jourSemaineNum2 = $jourSemaineNum;
                            }
                            ?>
                        </select>

                        <label for="maire" class="pt-3">Horaires disponibles :</label>
                        <select class="form-control" name="id_reservation" required id="horairesMassage" disabled>
                            <option value="" selected disabled>Sélectionner dans un premier temps un jour</option>
                        </select>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <input type="hidden" name="option_email_priseRdv_prAdmin" value="<?= $parametrages[0]->actif ?>">
                        <button type="submit" id="prendreRendezvousModal1" class="btn btn-secondary btn-sm" style="width: 90%;" disabled>Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de prise de réservation pour une des machines (type = 2) -->
<div class="modal fade" id="rendezvousMachine" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordUti.php">
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Prendre une réservation de cryothérapie compressive ou de pressothérapie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="alert alert-warning small">
                            <i class="fa-solid fa-clock"></i> Une réservation est égale à <b>une séance de 30 minutes</b>. Pour prendre un rendez-vous d'une heure ou <b>une autre prestation</b>, merci de prendre une autre réservation à la suite de la précédente.<br />
                            <i class="fas fa-lightbulb"></i> Bon à savoir, vous avez jusqu'à <b>48 heures avant</b> le rendez-vous pour modifier votre réservation, soit l'avant veille.
                        </div>

                        <label for="maire">Type de bottes :</label>
                        <select class="form-control" name="id_type_machine" required>
                            <option value="" selected disabled>Sélectionner un type</option>
                            <?php
                            foreach ($typesMachine as $typeMachine) {
                            ?>
                                <option value="<?= $typeMachine->id_type_machine; ?>"><?= $typeMachine->libelle; ?></option>

                            <?php
                            }
                            ?>
                        </select>

                        <label for="maire" class="pt-3">Jours disponibles :</label>
                        <select class="form-control" id="joursMachine" required>
                            <option value="" selected disabled>Sélectionner un jour</option>
                            <?php
                            $i = 0;
                            $jourSemaineNum2 = 0;
                            foreach ($joursMachine as $jourMachine) {
                                $annee = substr($jourMachine->date_reservation, 0, 4);
                                $mois = substr($jourMachine->date_reservation, 5, 2);
                                $jour = substr($jourMachine->date_reservation, 8, 2);
                                $dateNumerique = $jour . "/" . $mois . "/" . $annee;

                                $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
                                $jourSemaineNum = date('N', $timestamp);
                                $jourSemaine = array("", "Lundi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "Mardi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&thinsp;", "Mercredi&nbsp;&thinsp;", "Jeudi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "Vendredi&nbsp;&nbsp;", "Samedi&nbsp;&nbsp;&nbsp;&thinsp;&thinsp;", "Dimanche");
                                $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
                                list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);
                                $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
                                $date = $jourSemaine[$jourSemaineNum] . " " . $jour . " " . $moisAnnee[$moisNum] . " " . $anneeNum;

                                if($jourSemaineNum <= $jourSemaineNum2 && $i > 0) {
                                ?>
                                    <option disabled> Nouvelle semaine : </option>
                                <?php
                                }
                                $i = $i + 1;
                                ?>

                                <option value="<?= $annee . "-" . $mois . "-" . $jour; ?>"><?= $date ?></option>

                            <?php
                            $jourSemaineNum2 = $jourSemaineNum;
                            }
                            ?>
                        </select>

                        <label for="maire" class="pt-3">Horaires disponibles :</label>
                        <select class="form-control" name="id_reservation" required id="horairesMachine" disabled>
                            <option value='' selected disabled>Sélectionner dans un premier temps un jour</option>
                        </select>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <input type="hidden" name="option_email_priseRdv_prAdmin" value="<?= $parametrages[0]->actif ?>">
                        <button type="submit" id="prendreRendezvousModal2" class="btn btn-secondary btn-sm" style="width: 90%;" disabled>Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'édition du profil -->
<div class="modal fade" id="editProfilModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordUti.php">
            <div class="modal-content">
                <div class="modal-header primary">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Modifier mon profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group pb-2">
                        <label for="email">Adresse email :</label>
                        <input type="text" class="form-control" value="<?= $utilisateur[0]->email ?>" disabled>
                    </div>
                    <div class="form-group pb-2">
                        <label for="prenom">Prénom :</label>
                        <input type="text" class="form-control" name="prenom" id="prenom" onChange="this.value=premierCaractereMaj(this.value)" value="<?= $utilisateur[0]->prenom ?>" required>
                    </div>
                    <div class="form-group pb-2">
                        <label for="nom">Nom :</label>
                        <input type="text" class="form-control uppercase" name="nom" id="nom" onkeyup="this.value=this.value.toUpperCase()" value="<?= $utilisateur[0]->nom ?>" required>
                    </div>
                    <div class="form-group pb-2">
                        <label for="telephone">Téléphone :</label>
                        <input type="text" class="form-control" name="telephone" id="telephone" value="<?= $utilisateur[0]->telephone ?>">
                    </div>
                    <?php
                        $date_derniere_co = $utilisateur[0]->date_derniere_co;
                        $date_derniere_co = substr($date_derniere_co, 0, 10);
                    ?>
                    <div class="form-group pb-2">
                        <label for="date_inscription">Date dernière connexion :</label>
                        <input type="date" class="form-control" value="<?= $date_derniere_co ?>" disabled>
                    </div>
                    <div class="form-group pb-2">
                        <label for="date_inscription">Date d'inscription :</label>
                        <input type="date" class="form-control" value="<?= $utilisateur[0]->date_inscription ?>" disabled>
                    </div>
                    <div class="form-group pt-2 pb-2 text-center">
                        <label for="mentions_legales"><a href="mentions-legales.php" target="_blank">Conditions Générales d'Utilisation</a> :</label>
                        <input type="checkbox" class="col-2 form-check-input"  <?php if($utilisateur[0]->mentions_legales == "1") { ?> checked <?php } ?> disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="editProfilModal">
                        <input type="hidden" name="email" value="<?= $utilisateur[0]->email ?>">
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 90%;" id="btnEditProfil">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de changement de mot de passe -->
<div class="modal fade" id="editPwdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordUti.php" id="formEditPassword">
            <div class="modal-content">
                <div class="modal-header text-white warning">
                    <h5 class="modal-title"><i class="fa-solid fa-key"></i> Changer mon mot de passe</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group pb-2">
                        <label for="mdp">Mot de passe actuel :</label>
                        <div class="input-group">
                            <input type="password" class="form-control" <?php if(!empty($utilisateur[0]->mdp_temp)) { ?> value="<?= $utilisateur[0]->mdp_temp ?>" <?php } ?> name="mdp" id="mdp" required>
                            <div class="input-group-append toggle-password newCursor">
                                <span class="input-group-text h-100">
                                    <i class="fa fa-eye toggle-fa"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pb-2">
                        <label for="newMdp">Nouveau mot de passe :</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="newMdp" id="newMdp" data-minlength="6" required>
                            <div class="input-group-append toggle-password newCursor">
                                <span class="input-group-text h-100">
                                    <i class="fa fa-eye toggle-fa"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pb-2">
                        <label for="repeteMdp">Confirmez le nouveau mot de passe :</label>
                        <div class="input-group">
                            <input type="password" class="form-control"  name="repeteMdp" id="repeteMdp" data-minlength="6" onBlur="verifMdp()" required>
                            <div class="input-group-append toggle-password newCursor">
                                <span class="input-group-text h-100">
                                    <i class="fa fa-eye toggle-fa"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="container pb-2">
                        <div class="row justify-content-center align-items-center">
                            <a href="#" class="btn btn-warning btn-sm" style="width: 90%;">Tester le nouveau mot de passe</a>
                        </div>
                    </div>

                    <label class="col-12" id="texteErreur">&nbsp;</label>
                </div>

                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="editPwdModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-secondary btn-sm disabled" style="width: 90%;" id="btnEditPwd" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Enregistrement en cours">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale pour retirer une réservation -->
<div class="modal fade" id="btnRetirerRdvUti" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header secondary">
                <h5 class="modal-title text-white"><i class="fa fa-times"></i> Retirer cette réservation de votre liste</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="alert alert-warning small">
                    <i class="fa fa-exclamation-triangle"></i> Vous êtes sur le point de retirer ce rendez-vous. Il sera libéré pour un autre client.
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-5">
                    <a href="#" id="btnCloseRetirerRdv" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-success btn-sm text-white" style="width: 90%;" id="btnSubmitRetirerRdvUti">Retirer <i class="fas fa-arrow-left"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour editer le commentaire -->
<div class="modal fade" id="btnEditerCommentaireClient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordUti.php">
            <div class="modal-content">
                <div class="modal-header danger text-white">
                <h5 class="modal-title"><i class="fa fa-comment"></i> Commentaire pour cette  réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Commentaire visible par le praticien : 
                        <input type="text" class="form-control" name="commentaireClient" id="commentaireClient" placeholder="Ecrivez ici votre commentaire">              
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="editCommentaireClientModal">
                        <input type="hidden" name="id_reservation" id="reservation_id">
                        <button type="submit" class="btn btn-secondary btn-sm text-white" style="width: 90%;" id="btnSubmitEditerCommentaireClient">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<script src="../contenu/js/tableaudebordUti.js"></script>