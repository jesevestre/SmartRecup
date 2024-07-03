<?php session_start(); ?>
<?php include("../contenu/connexion/BDDIntersection.php"); ?>
<?php require "../service/compteur.php"; ?>
<?php include "entetedepage.php"; ?>

<?php
$date_aujourdhui = date("Y-m-d");
$dateHeure_aujourdhui = date("Y-m-dTh:00");
$dateHeure_aujourdhui1 = substr($dateHeure_aujourdhui, 0, 10);
$dateHeure_aujourdhui2 = substr($dateHeure_aujourdhui, 13, 6);
$dateHeure_aujourdhui = $dateHeure_aujourdhui1 . $dateHeure_aujourdhui2;

$date_demain = date("Y-m-d", strtotime("+1 day"));
$dateHeure_demain = date("Y-m-dTh:00", strtotime("+1 day"));
$dateHeure_demain1 = substr($dateHeure_demain, 0, 10);
$dateHeure_demain2 = substr($dateHeure_demain, 14, 6);
$dateHeure_demain = $dateHeure_demain1 . "T" . $dateHeure_demain2;

$date_apres_demain = date("Y-m-d", strtotime("+2 day"));
$date_apres_apres_demain = date("Y-m-d", strtotime("+3 day"));

// Récupération des informations personnelles de l'utilisateur
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $sql = "SELECT * FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
    $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);
}

// Liste des utilisateurs pour gérer les comptes à bloquer
$sql = "SELECT *
        FROM Utilisateurs
        WHERE administrateur = 0
        ORDER BY bloque ASC, prenom ASC, nom ASC"; 
$req = $pdo->prepare($sql);
$req->execute();
$gestionUtilisateurs = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours de réservations disponibles
$sql = "SELECT id AS id_reservation, DATE_FORMAT(date, '%Y-%m-%d') AS date_reservation 
    FROM Reservations 
    WHERE id_utilisateur IS NULL AND DATE_FORMAT(date, '%Y-%m-%d') >= ?
    GROUP BY date_reservation"; 
$req = $pdo->prepare($sql);
$req->execute(array($date_aujourdhui));
$joursReservations = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours de réservations disponibles pour filtreAutresJoursModal
$sql = "SELECT id AS id_reservation, DATE_FORMAT(date, '%Y-%m-%d') AS date_reservation 
    FROM Reservations 
    WHERE id_utilisateur IS NULL AND id_type = 1 AND DATE_FORMAT(date, '%Y-%m-%d') >= ?
    GROUP BY date_reservation"; 
$req = $pdo->prepare($sql);
$req->execute(array($date_apres_apres_demain));
$filtreAutresJours = $req->fetchAll(PDO::FETCH_OBJ);

// Récupération des réservations pour l'administrateur
$bouton = $_GET["bouton"];
$filtreAutresJoursModal = $_GET["filtreAutresJoursModal"];
if($bouton == "demain") {
    $option = "DATE(date) = DATE(NOW() + INTERVAL 1 DAY)";
} elseif($bouton == "apresdemain") {
    $option = "DATE(date) = DATE(NOW() + INTERVAL 2 DAY)";
} elseif($bouton == "autresjours" && empty($filtreAutresJoursModal)) {
    $option = "DATE(date) > DATE(NOW() + INTERVAL 2 DAY)";
} elseif($bouton == "autresjours" && !empty($filtreAutresJoursModal)) {
    $option = "DATE('$filtreAutresJoursModal') = DATE_FORMAT(Reservations.date, '%Y-%m-%d')";
} else { /* ($bouton == "aujourdhui") laisser en else */
    $option = "DATE(date) = DATE(NOW())";
}

$sql = "SELECT Reservations.id AS reservation_id, date, commentaireClient, commentaireAdmins, Reservations.id_utilisateur AS utilisateur_id, prenom, nom, email, telephone, type_machine.libelle AS libelle_type_machine, type_massage.libelle AS libelle_type_massage, Utilisateurs.id AS utilisateur_id
        FROM Reservations
        JOIN Utilisateurs ON Reservations.id_utilisateur = Utilisateurs.id
        LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
        LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
        WHERE $option
        ORDER BY date ASC";
$req = $pdo->prepare($sql);
$req->execute();
$reservationsAdministrateur = $req->fetchAll(PDO::FETCH_OBJ);
?>

<?php
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
                $message = "<b>L'enregistrement n'a pas fonctionné, veuillez réessayer.</b>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if($error == "error2") {
                $message = "<b>Assurez-vous bien que tous les champs obligatoires soient renseignés.</b>";
                $icone = "fa fa-exclamation-triangle";
                $color = "alert-warning";
            } else if($success == "successConnexion") {
                $message = "<b>Bienvenue " . $utilisateur[0]->prenom . " !</b> A partir de cette interface, vous pouvez gérer les réservations des clients et prendre note de ceux réservés.";
                $icone = "fas fa-hand-holding-medical";
                $color = "alert-success";
            } else if($success == "success2") {
                $message = "<b>Le réservation a été ajouté.</b> Il pourra être réservé par les utilisateurs.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success3") {
                $message = "<b>Le rendez-vous a été supprimer.</b> Il n'est plus disponible à la réservation par les utilisateurs.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success4") {
                $message = "<b>Le rendez-vous a été libéré</b> pour un autre client.";
                $icone = "fa-solid fa-check";
                $color = "alert-success";
            } else if($success == "success5") {
                $message = "<b>Le commentaire de la réservation a été mis à jour.</b>";
                $icone = "fas fa-arrow-right";
                $color = "alert-success";
            } else if($success == "success6") {
                $message = "<b>Le statut de l'utilisateur a été mis à jour.</b>";
                $icone = "fas fa-arrow-right";
                $color = "alert-success";
            } else if($success == "success7") {
                $message = "<b>La réservation a été prise par un client par vos soins.</b> Vous pourrez visualiser son identité dans le commentaire.";
                $icone = "fas fa-arrow-right";
                $color = "alert-success";
            } else if($bouton == "aujourdhui" || $bouton == "demain" || $bouton == "apresdemain" || $bouton == "autresjours") {
                $message = "<b>Les filtres</b> vous permettent d'affiner vos recherches concernant les réservations des clients.";
                $icone = "fas fa-arrow-right";
                $color = "alert-success";
            }
        }
        ?>
        <div class="col-11 cadrageMessage">
            <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
                <i class="<?= $icone ?>"></i> <?= $message; ?>
            </div>
            <div id="divMessage2"></div>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <!-- Début menu du haut de page -->
        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#gestion" class="btn"><i class="fa-2x fa fa-tools icone-color"></i></a>
                <a class="btn a_icone-color" data-bs-toggle="modal" data-bs-target="#rendezvousPrendre" data-bs-backdrop="static"><i class="fa-2x fa-solid fa-handshake-angle icone-color"></i></a>
                <a href="#les_rdv" class="btn a_icone-color"><i class="fa-2x fa-solid fa-eye icone-color"></i></a>
                <a href="deconnexion.php" class="btn a_icone-color"><i class="fa-2x fas fa-sign-out-alt icone-color"></i></a>
            </div>
        </div>
        <!-- Fin menu du haut de page -->

        <!-- Début de la gestion des informations du clients -->
        <div id="gestion" class="col-12 mt-3">
            <h3><i class="fa fa-tools"></i> Espace gestion :</h3>

            <div class="row mt-1">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-success mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousAjout" data-bs-backdrop="static"><i class="fa-sharp fa-solid fa-calendar-check"></i> Ajouter une réservation</a>
                </div>
                <!-- Bouton temporaire -->
                <!-- <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <form method="post" action="http://smartrécup.fr/service/serviceCrontab.php">
                        <input type="submit" value="Ne pas cliquer"></input>
                    </form>
                </div> -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-danger mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousPrendre" data-bs-backdrop="static"><i class="fa-solid fa-handshake-angle"></i> Prendre une réservation</a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-secondary mx-auto" data-bs-toggle="modal" data-bs-target="#rendezvousSupprimer" data-bs-backdrop="static"><i class="fa fa-trash"></i> Supprimer une réservation</a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-primary mx-auto" data-bs-toggle="modal" data-bs-target="#reservationUtilisateur" data-bs-backdrop="static"><i class="fa-solid fa-eye"></i> Réservations et infos client</a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-warning mx-auto" data-bs-toggle="modal" data-bs-target="#gestionUtilisateur" data-bs-backdrop="static"><i class="fa-solid fa-arrow-right-arrow-left"></i> Bloquer / débloquer client</a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-2 d-grid mx-auto">
                    <a class="col-12 btn btn-danger mx-auto" href="deconnexion.php" type="button" id="deconnexion"><i class="fas fa-sign-out-alt"></i>&nbsp;Déconnexion</a>
                </div>
            </div>
            <?php
            echo "<u>Nombre de connexions des clients :</u><br />";
            if(nombre_visites() == 0 || nombre_visites() == 1){
                echo nombre_visites() . " connexion depuis le " . date_premier_jour_visite_sortie();
            } else {
                echo nombre_visites() . " connexions depuis le " . date_premier_jour_visite_sortie();
            }
            echo "<br />";
            if(nombre_visites_today() == 0 || nombre_visites_today() == 1){
                echo nombre_visites_today() . " connexion depuis aujourd'hui.";
            } else {
                echo nombre_visites_today() . " connexions depuis aujourd'hui.";
            }
            ?> 
        </div>
        <!-- Fin de la gestion des informations du clients -->

        <hr class="horizontale_separation mt-3 mb-3">
        
        <!-- Début de la gestion des réservations prisent par les clients -->
        <div id="les_rdv" class="col-12">
            <h3><i class="fa-solid fa-eye"></i> Réservations clients :</h3>

            <div class="row pb-3 voir_border">
                <div class="col-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-warning col-3" onclick="window.location.href='../vue/tableaudebordAdmin.php?bouton=aujourdhui';">Aujourd'hui</button>
                    <button class="btn btn-danger col-3" onclick="window.location.href='../vue/tableaudebordAdmin.php?bouton=demain';">Demain</button>
                    <button class="btn btn-primary col-3" onclick="window.location.href='../vue/tableaudebordAdmin.php?bouton=apresdemain';">Après demain</button>
                    <button class="btn btn-secondary col-3" onclick="window.location.href='../vue/tableaudebordAdmin.php?bouton=autresjours';">Autres jours</button>
                </div>

                <?php
                if($bouton == "autresjours"){
                ?>
                    <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
                        <div class="row">
                            <div class="col-9 mt-2 mb-1">
                                <select class="form-control" name="filtreAutresJours">
                                <?php
                                if(!empty($filtreAutresJoursModal)){
                                    $annee = substr($filtreAutresJoursModal, 0, 4);
                                    $mois = substr($filtreAutresJoursModal, 5, 2);
                                    $jour = substr($filtreAutresJoursModal, 8, 2);
                                    $dateNumerique = $jour . "/" . $mois . "/" . $annee;

                                    $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
                                    $jourSemaineNum = date('N', $timestamp);
                                    $jourSemaine = array("", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
                                    $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
                                    list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);
                                    $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
                                    $date = $jourSemaine[$jourSemaineNum] . " " . $jour . " " . $moisAnnee[$moisNum] . " " . $anneeNum;
                                    $value = $annee . "-" . $mois . "-" . $jour;

                                    echo "<option value='$value' selected disabled> " . $date . " (filtre en cours)</option>";
                                } else {
                                    echo "<option value='' selected disabled>Cliquez ici pour selectionner un jour</option>";
                                }
                                $i = 0;
                                $jourSemaineNum2 = 0;
                                foreach ($filtreAutresJours as $jourReservation) {
                                    $annee = substr($jourReservation->date_reservation, 0, 4);
                                    $mois = substr($jourReservation->date_reservation, 5, 2);
                                    $jour = substr($jourReservation->date_reservation, 8, 2);
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
                            </div>
                            <div class="col-3 mt-1 mb-1">
                                <input type="hidden" name="action" value="filtreAutresJoursModal">
                                <button type="submit" class="btn btn-success btn-sm" style="width: 90%; height: 100%;" id="filtreAutresJoursModal">Filtrer <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                <?php
                }
                if(!empty($reservationsAdministrateur)) {
                    $datePrecedente = "";

                    foreach($reservationsAdministrateur AS $reservationAdministrateur):

                        $annee = substr($reservationAdministrateur->date, 0, 4);
                        $mois = substr($reservationAdministrateur->date, 5, 2);
                        $jour = substr($reservationAdministrateur->date, 8, 2);
                        $dateNumerique = $jour . "/" . $mois . "/" . $annee;

                        $heure = substr($reservationAdministrateur->date, 11, 2);
                        $minute = substr($reservationAdministrateur->date, 14, 2);
                        $time = $heure . "h" . $minute;

                        $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
                        $jourSemaineNum = date('N', $timestamp);
                        $jourSemaine = array("", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
                        $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
                        list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);
                        $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
                        $date = $jourSemaine[$jourSemaineNum] . " " . $jour . " " . $moisAnnee[$moisNum] . " " . $anneeNum;



                        $dateColor = substr($reservationAdministrateur->date, 0, 10);
                        if($dateColor == $date_aujourdhui) {
                            $themeCard = "text-white warning";
                        } elseif($dateColor == $date_demain) {
                            $themeCard = "text-white danger";
                        } elseif($dateColor == $date_apres_demain) {
                            $themeCard = "text-dark primary";
                        } elseif($dateColor < $date_apres_apres_demain) {
                            $themeCard = "warning";
                        } else {
                            $themeCard = "text-white warning";
                        }

                        if($dateColor == $date_aujourdhui) {
                            $telephone = $reservationAdministrateur->telephone;
                            $date = "Aujourd'hui à " . $time;
                        } else if($dateColor == $date_demain) {
                            $telephone = "<span style='color: #FFF;'>" . $reservationAdministrateur->telephone . "</span>";
                            $date = "Demain à " . $time;
                        } else if($dateColor == $date_apres_demain) {
                            $telephone = $reservationAdministrateur->telephone;
                            $date = "Après demain (" . $date . ") à " . $time;
                        } else {
                            $telephone = $reservationAdministrateur->telephone;
                            $telephone = "<span style='color: #FFF;'>" . $telephone . "</span>";
                            $annee = substr($reservationAdministrateur->date, 0, 4);
                            $mois = substr($reservationAdministrateur->date, 5, 2);
                            $jour = substr($reservationAdministrateur->date, 8, 2);
                            $date = $date . " à " . $time;
                        }
                    ?>

                    <?php
                        $dateActuelle = substr($reservationAdministrateur->date, 0, 10);
                        if($dateActuelle != $datePrecedente && $datePrecedente != "") {
                            echo "<hr class='horizontale_separation mt-2 mb-2'>";
                        }
                        $datePrecedente = substr($reservationAdministrateur->date, 0, 10);
                    ?>

                        <div class="col-12 mt-1">
                            <div class="card <?= $themeCard ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="card-title">
                                                <?php
                                                if($reservationAdministrateur->utilisateur_id == 64) {
                                                    echo $reservationAdministrateur->commentaireClient . (!empty($reservationAdministrateur->commentaireAdmins) ? " - " . $reservationAdministrateur->commentaireAdmins : "");
                                                } else {
                                                    echo $reservationAdministrateur->prenom . " " . $reservationAdministrateur->nom . (!empty($reservationAdministrateur->telephone) ? " - " . $telephone : "");
                                                }
                                                ?>
                                            </h5>
                                        </div>
                                        <div class="col-4">
                                            <b><?= (!empty($reservationAdministrateur->libelle_type_machine) ? $reservationAdministrateur->libelle_type_machine : $reservationAdministrateur->libelle_type_massage) ?></b>
                                        </div>
                                        <div class="col-4">
                                            <?= $date ?>
                                        </div>
                                        <div class="col-2">
                                        <?php 
                                            if($reservationAdministrateur->commentaireAdmins == NULL || $reservationAdministrateur->commentaireAdmins == "") { 
                                                if($reservationAdministrateur->commentaireClient == NULL || $reservationAdministrateur->commentaireClient == "") { 
                                                    $iconeCommentaire = "fas fa-comment text-white";
                                                } else {
                                                    $iconeCommentaire = "fas fa-comment text-white";
                                                }
                                            } else {
                                                $iconeCommentaire = "fa-solid fa-comments text-white";
                                            }
                                            $commentaireClient = str_replace("'", " ", $reservationAdministrateur->commentaireClient);
                                            $commentaireAdmins = str_replace("'", " ", $reservationAdministrateur->commentaireAdmins);
                                        ?>

                                            <a class="btn secondary mx-auto w-100" name="action" onclick="javascript:voir_commentaire('<?= $commentaireClient ?>', '<?= $commentaireAdmins ?>', '<?= $reservationAdministrateur->reservation_id ?>');">
                                            <i class="<?= $iconeCommentaire ?>"></i></a>
                                        </div>
                                        <div class="col-2">
                                            <a class="btn btn-success mx-auto w-100 btnRetirerRdvAdmin" data-ids="<?= $reservationAdministrateur->reservation_id . " " . $reservationAdministrateur->utilisateur_id ?>" data-bs-toggle="modal" data-bs-target="#btnRetirerRdvAdmin" href="#" data-bs-backdrop="static"><i class="fa fa-times"></i></a>
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
                            <?php
                            if(!empty($filtreAutresJoursModal)) {
                                echo "Vous n'avez pas de réservation pour cette date sélectionner.";
                            } else {
                                echo "Vous n'avez pas de réservation dans cette section.";
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- Fin de la gestion des réservations prisent par les clients -->

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

<!-- Modale d'ajout d'une réservation -->
<div class="modal fade" id="rendezvousAjout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header success text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Ajouter une réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> La réservation que vous ajouter ici sera visible par les clients. Veillez à ce que le nouveau rendez-vous n'en chevauche pas un <b>qui existe déjà</b>.<br />
                        <i class="fas fa-lightbulb"></i> Bon à savoir, vous avez la possiblité de visualiser les <b>réservations déjà existantes</b> en ouvrant la liste la plus en bas de cette modale.
                    </div>

                    <div class="form-group">
                        <div class="form-group pb-3">
                            <label for="type">Type :</label>
                            <div id="typeMessageOuMachine">
                                <label class="btnMassage" for="1">Massage ou ventouses</label>
                                <input type="radio" name="type" value="1">
                                <label class="btnVentouse" for="2">Presso ou cryothérapie</label>
                                <input type="radio" name="type" value="2">
                            </div>
                            <label for="dateTime" class="mt-3">Horaire :</label>
                            <div class="cursorGrab">
                                <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" value="<?= $dateHeure_demain ?>" min="<?= $dateHeure_demain ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <p class="text-center"><b>Liste des horaires déjà ajoutés :</b></p>

                    <label for="maire">Jours disponibles :</label>
                    <select class="form-control" id="joursReservations">
                        <option value="" selected disabled>Sélectionner un jour</option>
                        <?php
                        $i = 0;
                        $jourSemaineNum2 = 0;
                        foreach ($joursReservations as $jourReservation) {
                            $annee = substr($jourReservation->date_reservation, 0, 4);
                            $mois = substr($jourReservation->date_reservation, 5, 2);
                            $jour = substr($jourReservation->date_reservation, 8, 2);
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
                    <select class="form-control" name="id_reservation" id="horairesReservations" disabled>
                        <option value="" selected disabled>Sélectionner dans un premier temps un jour</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="ajoutRendezvousModal">
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 90%;" id="ajoutRendezvousModal" disabled>Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'ajout d'une réservation -->
<div class="modal fade" id="rendezvousPrendre" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-handshake-angle"></i> Prendre une réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> La réservation que vous prenez sera au nom d'un <b>utilisateur fictif</b>, c'est à vous de renseigner un certain nombre d'informations du client.<br />
                        <i class="fas fa-lightbulb"></i> Bon à savoir, vous pourrez consulter ces informations à tout moment dans <b>les commentaires</b> de la réservation.
                    </div>

                    <div class="form-group">
                        <label for="maire">Si le client est inscrit :</label>
                        <select class="form-control" name="id_utilisateur" id="id_utilisateur">
                            <option selected disabled>Sélectionner un client</option>
                            <?php
                            foreach ($gestionUtilisateurs as $gestionUtilisateur) {

                                if($gestionUtilisateur->bloque != 1) {
                                ?>
                                    <option value="<?= $gestionUtilisateur->id ?>"><?= $gestionUtilisateur->prenom . " " . $gestionUtilisateur->nom . " - " . $gestionUtilisateur->email ?></span></option>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group pt-3" id="commentaireAdminsDiv">
                        Si le client n'est pas inscrit, son identitité : 
                        <input type="text" class="form-control" name="commentaireClient" id="commentaireClientInput" <?php if(!empty($_SESSION["commentaireClient"])) { ?> value="<?= $_SESSION["commentaireClient"] ?>" <?php } ?> required>
                        Et son adresse email pour les rappels (champ optionnel) : 
                        <input type="email" class="form-control" name="commentaireAdmin" <?php if(!empty($_SESSION["commentaireAdmin"])) { ?> value="<?= $_SESSION["commentaireAdmin"] ?>" <?php } ?>>
                    </div>
 
                    <hr>

                    <label for="maire">Jours disponibles :</label>
                    <select class="form-control" id="joursReservations2">
                        <option <?php if(!empty($joursReservationsRetour)) { ?> value="<?= $joursReservationsRetour ?>" <?php } else { ?> value="" <?php } ?> selected disabled>Sélectionner un jour</option>
                        <?php
                        $i = 0;
                        $jourSemaineNum2 = 0;
                        foreach ($joursReservations as $jourReservation) {
                            $annee = substr($jourReservation->date_reservation, 0, 4);
                            $mois = substr($jourReservation->date_reservation, 5, 2);
                            $jour = substr($jourReservation->date_reservation, 8, 2);
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
                    <select class="form-control" name="horairesReservations2" id="horairesReservations2" required disabled>
                        <option value="" selected disabled>Sélectionner dans un premier temps un jour</option>
                    </select>

                    <label for="maire" class="pt-3">Précision du type :</label>
                    <select class="form-control" name="precision_type" id="typePrecision" required disabled>
                        <option value="" selected disabled>Sélectionner dans un deuxième temps un horaire</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="PrendreReservationModal">
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 90%;" id="PrendreReservationModal" disabled>Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de suppression d'une réservation -->
<div class="modal fade" id="rendezvousSupprimer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                    <h5 class="modal-title"><i class="fa fa-trash"></i> Supprimer une réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-lightbulb"></i> Bon à savoir, la liste des réservations supprimables comprend les séances qui sont <b>libres</b>.<br />
                        <i class="fa fa-exclamation-triangle"></i>  Vous devez d'abord <b>retirer la réservation</b> prise par le client avant de pouvoir la supprimer.
                    </div>



                    <label for="maire">Jours disponibles :</label>
                    <select class="form-control" id="joursReservationsASupprimer" required>
                        <option value="" selected disabled>Sélectionner un jour</option>
                        <?php
                        $i = 0;
                        $jourSemaineNum2 = 0;
                        foreach ($joursReservations as $jourReservations) {
                            $annee = substr($jourReservations->date_reservation, 0, 4);
                            $mois = substr($jourReservations->date_reservation, 5, 2);
                            $jour = substr($jourReservations->date_reservation, 8, 2);
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
                    <select class="form-control" name="id_reservation" id="horairesReservationsASupprimer" required disabled>
                        <option value="" selected disabled>Sélectionner dans un premier temps un jour</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="supprimerRendezvousModal">
                        <input type="hidden" name="id_utilisateur" value="<?= $utilisateur[0]->id ?>">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="supprimerRendezvousModal" disabled>Supprimer <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale réservation par client -->
<div class="modal fade" id="reservationUtilisateur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header primary">
                    <h5 class="modal-title"><i class="fa-solid fa-eye"></i> Réservations et infos client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-lightbulb"></i> Bon à savoir, vous avez la possiblité de visualiser l'<b>historique des réservations</b> prises par les clients.<br />
                        <i class="fa fa-exclamation-triangle"></i> Dans la liste des clients, seul les clients <b>actifs</b> sont affichés et seul les réservations <b>à venir</b> sont retirables.<br />
                    </div>
                    <div class="form-group">
                        <label for="maire">Client souhaité :</label>
                        <select class="form-control" name="id_utilisateur_reservation" id="id_utilisateur_reservation" required>
                            <option value="" selected disabled>Sélectionner un client</option>
                            <?php
                            foreach ($gestionUtilisateurs as $gestionUtilisateur) {

                                if($gestionUtilisateur->bloque == 0) {
                                ?>

                                <option value="<?= $gestionUtilisateur->id ?>"><?= $gestionUtilisateur->prenom . " " . $gestionUtilisateur->nom . "  |  ACTIF" ?></span></option>

                            <?php
                                }
                            }
                            ?>
                        </select>

                        <label for="maire" class="pt-3">Réservations :</label>
                        <select class="form-control" name="retirerRendezvousModal" id="reservationsClient" required disabled>
                            <option value="" selected disabled>Sélectionner dans un premier temps un client</option>
                        </select>
                        
                        <label for="maire" class="pt-3">Informations :</label>
                        <input class="form-control" id="InformationsClient0" placeholder="Sélectionner dans un premier temps un client" disabled>
                        <input class="form-control" id="InformationsClient1" placeholder="Sélectionner dans un premier temps un client" disabled>
                        <input class="form-control" id="InformationsClient2" placeholder="Sélectionner dans un premier temps un client" disabled>
                        <input class="form-control" id="InformationsClient3" placeholder="Sélectionner dans un premier temps un client" disabled>
                    
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="retirerRendezvousModal">
                        <button type="submit" class="btn btn-success btn-sm text-white" style="width: 90%;" id="btnSubmitRetirerRdvAdmin2" disabled>Retirer le rdv <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale pour bloquer / débloquer un client -->
<div class="modal fade" id="gestionUtilisateur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header warning text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-arrow-right-arrow-left"></i> Bloquer / débloquer un client</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> En bloquant un utilisateur, il n'aura plus la possibilité de se connecter à son compte.<br />
                        <i class="fas fa-lightbulb"></i> Bon à savoir, dans la liste ci-dessous, vous pouvez différencier le statut des utilisateurs en fin de ligne et <b>inverser son statut</b>.
                    </div>
                    <div class="form-group">
                        <label for="maire">Utilisateurs à bloquer ou à débloquer :</label>
                        <select class="form-control" name="id_utilisateur" id="id_utilisateur_a_boquer">
                            <option selected disabled>Sélectionner un client</option>
                            <?php
                            foreach ($gestionUtilisateurs as $gestionUtilisateur) {

                                if($gestionUtilisateur->bloque == 1) {
                                    $bloque = "BLOQUÉ";
                                    $style = "style='background: #6c757d; color: #FFF;'";
                                } else {
                                    $bloque = "ACTIF";
                                    $style = "";
                                }
                                ?>

                                <option <?= $style ?>value="<?= $gestionUtilisateur->id ?>"><?= $gestionUtilisateur->prenom . " " . $gestionUtilisateur->nom . " | " . $bloque ?></span></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="bloquerDebloquerUtilisateurModal">
                        <button type="submit" class="btn btn-warning btn-sm" style="width: 90%;" id="bloquerUtilisateur" disabled>Bloquer / débloquer <i class="fa-solid fa-arrow-right-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale pour retirer une réservation -->
<div class="modal fade" id="btnRetirerRdvAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- <form method="POST" action="#" id="formDeleteCommentaireMairie"> -->
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                    <h5 class="modal-title"><i class="fa fa-times"></i> Retirer cette réservation du client</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> Vous êtes sur le point de retirer cette réservation. Elle sera libérée pour un autre client et si besoin, vous pourrez par la suite la retirer des réservations. <b>Attention, cette action est irrévocable et enverra un email au client</b>.<br />
                        <i class="fas fa-lightbulb"></i> Bon à savoir, vous n'avez pas la même interface que les clients, ils ne leurs est pas possible de retirer leur réservations <b>moins d'un jour avant le rendez-vous</b>.
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" id="btnCloseRetirerRdv" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-success btn-sm text-white" style="width: 90%;" id="btnSubmitRetirerRdvAdmin">Retirer <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        <!-- </form> -->
    </div>
</div>

<!-- Modale pour voir le commentaire -->
<div class="modal fade" id="btnCommentaireClientEtAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurTableaudebordAdmin.php">
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                <h5 class="modal-title"><i class="fa fa-comment"></i> Commentaire pour cette réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group pb-3">
                        Commentaire du client : <input class="form-control" id="commentaireClient" disabled>
                    </div>
                    <div class="form-group">
                        Commentaire (visible que par le praticien) : <input class="form-control" name="commentaireAdmins" id="commentaireAdmins">
                    </div>
                </div>
                <div class="modal-footer">
                <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="editCommentaireAdminsModal">
                        <input type="hidden" name="id_reservation" id="reservation_id">
                        <button type="submit" class="btn btn-secondary btn-sm text-white" style="width: 90%;" id="btnSubmitEditerCommentaireAdmins">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<script src="../contenu/js/tableaudebordAdmin.js"></script>