<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleEvenement.php"); ?>

<?php
if(isset($_POST["action"]) && $_POST["action"] == "prendreReservation") {
	$id_reservation = $_POST["id_reservation"];
	$client = $_POST["client"];
    $telephone = $_POST["telephone"];

	if(!empty($id_reservation) && !empty($client) && !empty($telephone)) {
		$result = prendreReservation($pdo, $id_reservation, $client, $telephone);
	}

	echo "<script>window.location.href='../vue/evenement.php';</script>"; exit;
}

if(isset($_POST["action"]) && $_POST["action"] == "libererReservation") {
    $id_reservation = $_POST["id_reservation"];

	if(!empty($id_reservation)) {
		$result = libererReservation($pdo, $id_reservation);
	}

	echo "<script>window.location.href='../vue/evenement.php';</script>"; exit;
}

if(isset($_POST["action"]) && $_POST["action"] == "supprimerReservation") {
	$id_reservation = $_POST["id_reservation"];

	if(!empty($id_reservation)) {

		$result = supprimerReservation($pdo, $id_reservation);
	}

	echo "<script>window.location.href='../vue/evenement.php';</script>"; exit;
}

if(isset($_POST["action"]) && $_POST["action"] == "ajouterReservation") {
	$time = $_POST["time"];

	if(!empty($time)) {
		$result = ajouterReservation($pdo, $time);
	}

	echo "<script>window.location.href='../vue/evenement.php';</script>"; exit;
}
?>