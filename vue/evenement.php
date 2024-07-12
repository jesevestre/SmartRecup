<?php include "entetedepage.php"; ?>
<?php include("../contenu/connexion/BDDIntersection.php"); ?>

<?php
// Paramétrages
$sql = "SELECT *
        FROM Parametrages"; 
$req = $pdo->prepare($sql);
$req->execute();
$parametrages = $req->fetchAll(PDO::FETCH_OBJ);

// Liste des jours de réservations disponibles
$sql = "SELECT id, time, client, telephone
        FROM Evenement"; 
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
                        $heurePrecedente = substr($evenementReservation->time, 0, 2);

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
                                                <a class="btn btn-primary mx-auto w-100" name="action" onclick="javascript:voir_telephone('<?= $commentaireClient ?>', '<?= $commentaireAdmins ?>', '<?= $evenementReservation->id ?>');"><i class="fa-solid fa-check"></i></a>
                                            <?php
                                            } else {
                                            ?>
                                                <a class="btn btn-secondary mx-auto w-100 btnRetirerRdvAdmin" data-ids="<?= $evenementReservation->id ?>" data-bs-toggle="modal" data-bs-target="#btnRetirerRdvAdmin" href="#" data-bs-backdrop="static"><i class="fa-solid fa-plus"></i></a>
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
            <a id="" class="btn col-10 mx-auto"><i class="fa-solid fa-mound"></i></a>
        </div>
        <div class="col-4 d-grid mx-auto">
            <a id="" class="btn col-10 mx-auto"><i class="fa-solid fa-mound"></i></a>
        </div>
        <div class="col-4 d-grid mx-auto">
            <a id="" class="btn col-10 mx-auto"><i class="fa-solid fa-mound"></i></a>
        </div>
    </div>

</div>

<script src="../contenu/js/prestations.js"></script>

<?php include "pieddepage.php"; ?>