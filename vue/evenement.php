<?php include("../contenu/connexion/BDDIntersection.php"); ?>
<?php include "entetedepage.php"; ?>

<?php
// Paramétrages
$sql = "SELECT *
        FROM Parametrages"; 
$req = $pdo->prepare($sql);
$req->execute();
$parametrages = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours de réservations disponibles
$sql = "SELECT id, time, client, telephone
        FROM Evenement
        ORDER BY time"; 
$req = $pdo->prepare($sql);
$req->execute();
$evenementReservations = $req->fetchAll(PDO::FETCH_OBJ);
?>

<div class="row d-flex justify-content-center mt-5 pb-5">

    <div class="mb-4 cadrage">
        <h3><?php echo $parametrages[3]->champ_texte ?></h3>
    </div>

    <div class="col-5 cadrage pb-4">
        <div class="col-12">
            <p class="justifie"><?php echo $parametrages[4]->champ_textearea ?></p>
        </div>
        <div class="col-md-6">
            <img class="photo-img" src="../contenu/images/MaximePresentation.jpeg" alt="Image du praticien en train de pratiquer" />
        </div>
    </div>

    <div class="col-7 cadrage">
        <div class="row">
            <div class="col-12">
                <div>
                <?php
                if(!empty($evenementReservations)) {
                    $heurePrecedente = "";

                    foreach($evenementReservations AS $evenementReservation):

                        $heure = substr($evenementReservation->time, 0, 2);
                        $minute = substr($evenementReservation->time, 3, 2);
                        $time = $heure . "h" . $minute;
                    ?>

                    <?php
                        $heureActuelle = substr($evenementReservation->time, 0, 2);
                        if($heureActuelle != $heurePrecedente && $heurePrecedente != "") {
                            echo "<hr class='horizontale_separation mt-2 mb-2'>";
                        }
                        $heurePrecedente = $heureActuelle;
                    ?>

                        <div class="col-12 mt-1">
                            <div class="card text-white warning">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <b><?= (!empty($evenementReservation->client) ? "Réservé" : "Libre") ?></b>
                                        </div>
                                        <div class="col-3">
                                            <?= $time ?>
                                        </div>
                                        <div class="col-4">
                                            <?= (!empty($evenementReservation->client) ? $evenementReservation->client : "") ?>
                                        </div>
                                        <div class="col-2">
                                            <?php
                                            if (!empty($evenementReservation->client)) {
                                            ?>
                                                <a class="btn btn-secondary mx-auto w-100" name="action" onclick="javascript:voir_infos_client('<?= $evenementReservation->telephone ?>', '<?= $evenementReservation->client ?>', '<?= $evenementReservation->id ?>')"><i class="fa-solid fa-check"></i></a>
                                            <?php
                                            } else {
                                            ?>
                                                <a class="btn btn-primary mx-auto w-100 prendreReservation" data-ids="<?= $evenementReservation->id . " " . $evenementReservation->time ?>" data-bs-toggle="modal" data-bs-target="#prendreReservation" href="#" data-bs-backdrop="static"><i class="fa-solid fa-plus"></i></a>
                                            <?php
                                            }
                                            ?>
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
                                echo "Il n'y a pas encore de réservation disponible.";
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-5 d-grid d-flex col-12 mx-auto">
        <a href="../" type="button" id="btnRetour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</a>
    </div>
    <div class="d-grid d-flex col-12 mx-auto">
        <div class="col-4 d-grid mx-auto">
            <a class="btn col-10 mx-auto" data-bs-toggle="modal" data-bs-target="#ajouterReservation" data-bs-backdrop="static"><i class="fa-solid fa-mound"></i></a>
        </div>
        <div class="col-4 d-grid mx-auto">
            <a class="btn col-10 mx-auto" data-bs-toggle="modal" data-bs-target="#libererReservation" data-bs-backdrop="static"><i class="fa-solid fa-mound"></i></a>
        </div>
        <div class="col-4 d-grid mx-auto">
            <a class="btn col-10 mx-auto" data-bs-toggle="modal" data-bs-target="#supprimerReservation" data-bs-backdrop="static"><i class="fa-solid fa-mound"></i></a>
        </div>
    </div>

</div>

<!-- Modale pour voir les informations clients -->
<div class="modal fade" id="btnVoirTelephone" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurEvenement.php">
            <div class="modal-content">
                <div class="modal-header secondary">
                <h5 class="modal-title text-white"><i class="fa fa-comment"></i> Modale d'information du client</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="form-group pb-3">
                        Identité : <input type="text" class="form-control" id="identite" disabled>
                    </div>
                    <div class="form-group pb-3">
                        Téléphone : <input type="text" class="form-control" id="telephone" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="col-12">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fas fa-arrow-left"></i> Retour</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'ajout pour cette réservation -->
<div class="modal fade" id="prendreReservation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurEvenement.php">
            <div class="modal-content">
                <div class="modal-header primary">
                    <h5 class="modal-title"><i class="fa-solid fa-plus"></i> M'ajouter pour cet horaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-lightbulb"></i> Cette action permettra de <b>réserver le créneau</b> de <a id="time"></a>.<br />
                        <i class="fa fa-exclamation-triangle"></i> Les deux champs ci-dessous sont <b>obligatoires</b>. Rien ne se passera s'ils ne sont pas renseignés.
                    </div>
                    <div class="form-group pb-3">
                        <label for="telephone">Votre prénom et nom :</label>
                        <input type="text" class="form-control mb-3" name="client">

                        <label for="telephone">Votre numéro de téléphone :</label>
                        <input type="text" class="form-control" name="telephone" maxlength="10">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" id="btnCloseRetirerRdv" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="prendreReservation">
                        <input type="hidden" name="id_reservation" id="id_reservation">
                        <button type="submit" class="btn btn-secondary btn-sm text-white" style="width: 90%;">M'ajouter <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale d'ajout d'une réservation -->
<div class="modal fade" id="ajouterReservation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurEvenement.php">
            <div class="modal-content">
                <div class="modal-header secondary text-white">
                    <h5 class="modal-title"><i class="fa-sharp fa-solid fa-calendar-check"></i> Ajouter une réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-lightbulb"></i> La réservation que vous ajouter ici <b>sera visible</b> par les clients.
                    </div>

                    <div class="form-group pb-3">
                        <label for="time" class="mt-3">Horaire :</label>
                        <div class="cursorGrab" id="libererBoutonEnregistrerAjouter">
                            <input type="time" class="form-control" name="time" value="10:00" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-5">
                        <a href="#" data-bs-dismiss="modal" type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-times"></i> Annuler</a>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="action" value="ajouterReservation">
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 90%;">Enregistrer <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale pour vider une réservation -->
<div class="modal fade" id="libererReservation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurEvenement.php">
            <div class="modal-content">
                <div class="modal-header warning text-white">
                    <h5 class="modal-title"><i class="fa fa-times"></i> Retirer la réservation d'un client</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> La réservation que vous sélectionner <b>sera libérée</b> pour un autre client.
                    </div>

                    <div class="form-group pb-3">
                        <label for="maire">Horaires :</label>
                        <select class="form-control" name="id_reservation" id="retirerBoutonEnregistrerSuppr" required>
                            <option value="" selected disabled>Sélectionner un horaire</option>
                            <?php
                            $heurePrecedente = "";
                            foreach ($evenementReservations as $evenementReservation) {
                                $heure = substr($evenementReservation->time, 0, 2);
                                $minute = substr($evenementReservation->time, 3, 2);

                                $heureActuelle = substr($evenementReservation->time, 0, 2);
                                if($heureActuelle != $heurePrecedente && $heurePrecedente != "") {
                                ?>
                                    <option disabled> -------------- </option>
                                <?php
                                }
                                $heurePrecedente = $heureActuelle;
                                ?>

                                <option value="<?= $evenementReservation->id ?>" <?= (!empty($evenementReservation->client) ? "" : "disabled") ?> ><?= $heure . "h" . $minute; ?> <?= (!empty($evenementReservation->client) ? " - Réservée par " . $evenementReservation->client : " - Libre") ?></option>

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
                        <input type="hidden" name="action" value="libererReservation">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="retirerReservationModale" disabled>Retirer <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modale de suppression d'une réservation -->
<div class="modal fade" id="supprimerReservation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="../controlleur/controlleurEvenement.php">
            <div class="modal-content">
                <div class="modal-header success text-white">
                    <h5 class="modal-title"><i class="fa fa-trash"></i> Supprimer une réservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle"></i> La réservation que vous supprimer sera <b>définitivement supprimée</b>.
                    </div>

                    <div class="form-group pb-3">
                        <label for="maire">Horaires :</label>
                        <select class="form-control" name="id_reservation"  id="libererBoutonEnregistrerSuppr" required>
                            <option value="" selected disabled>Sélectionner un horaire</option>
                            <?php
                            $heurePrecedente = "";
                            foreach ($evenementReservations as $evenementReservation) {
                                $heure = substr($evenementReservation->time, 0, 2);
                                $minute = substr($evenementReservation->time, 3, 2);

                                $heureActuelle = substr($evenementReservation->time, 0, 2);
                                if($heureActuelle != $heurePrecedente && $heurePrecedente != "") {
                                ?>
                                    <option disabled> -------------- </option>
                                <?php
                                }
                                $heurePrecedente = $heureActuelle;
                                ?>

                                <option value="<?= $evenementReservation->id ?>"><?= $heure . "h" . $minute; ?><?= (!empty($evenementReservation->client) ? " - Réservée par " . $evenementReservation->client : " - Libre") ?></option>

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
                        <input type="hidden" name="action" value="supprimerReservation">
                        <button type="submit" class="btn btn-success btn-sm" style="width: 90%;" id="supprReservationModale" disabled>Supprimer <i class="fas fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="../contenu/js/evenement.js"></script>

<?php include "pieddepage.php"; ?>