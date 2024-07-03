<?php session_start(); ?>
<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleTableaudebordAdmin.php"); ?>
<?php include("../service/serviceEnvoiEmail.php"); ?>

<?php
if(isset($_POST["action"]) && $_POST["action"] == "ajoutRendezvousModal") {
    $dateTime = $_POST["dateTime"];
	$type = $_POST["type"];

	if(!empty($dateTime) && !empty($type)) {
		$result = ajoutRendezvous($pdo, $type, $dateTime);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success2';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "supprimerRendezvousModal") {
    $id_reservation = $_POST["id_reservation"];

	if(!empty($id_reservation)) {
		$result = supprimerRendezvous($pdo, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success3';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_GET["retirerRendezvousModal"]) || (isset($_POST["action"]) && $_POST["action"] == "retirerRendezvousModal")) {
	if(isset($_POST["action"]) && $_POST["action"] == "retirerRendezvousModal") {
		$id_utilisateur = $_POST["id_utilisateur_reservation"];
    	$id_reservation = $_POST["retirerRendezvousModal"];
	} else {
		$id_utilisateur = $_GET["id_utilisateur"];
		$id_reservation = $_GET["retirerRendezvousModal"];
	}

	if(!empty($id_utilisateur) && !empty($id_reservation)) {
		$result = retirerRendezvous($pdo, $id_reservation);

		if($id_utilisateur != 64) {
			envoi_email_annulation_client($pdo, $id_utilisateur, $id_reservation);
		}
		
		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success4';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "editCommentaireAdminsModal") {
    $id_reservation = $_POST["id_reservation"];
	$commentaireAdmins = $_POST["commentaireAdmins"];
	$commentaireAdmins = str_replace(array("%", "@", "\'", "\"", ";", "<", ">" ), " ", $commentaireAdmins);

	if(!empty($id_reservation)) {
		$result = editCommentaireAdmins($pdo, $commentaireAdmins, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success5';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "bloquerDebloquerUtilisateurModal") {
	$id_utilisateur = $_POST["id_utilisateur"];

	if(!empty($id_utilisateur)) {
		$result = bloquerDebloquerUtilisateur($pdo, $id_utilisateur);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success6';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "PrendreReservationModal") {
	$id_utilisateur = $_POST["id_utilisateur"];
	$commentaireClient = $_POST["commentaireClient"];
	$commentaireAdmin = $_POST["commentaireAdmin"];

	$precision_type = $_POST["precision_type"];
	$id_reservation = $_POST["horairesReservations2"];

	if(!empty($id_utilisateur || $commentaireClient) && !empty($precision_type) && !empty($id_reservation)) {
		$result = reservationPourClient($pdo, $id_utilisateur, $commentaireClient, $commentaireAdmin, $precision_type, $id_reservation);
		
		if(!empty($id_utilisateur)) {
			envoi_email_confirmation_client($pdo, $id_utilisateur, $id_reservation);
		}

		// Pour le retour, ne pas toujours avoir à retaper le nom et adresse email du client
		$_SESSION["commentaireClient"] = $_POST["commentaireClient"];
		$_SESSION["commentaireAdmin"] = $_POST["commentaireAdmin"];

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=success7';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordAdmin.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "filtreAutresJoursModal") {
	$filtreAutresJoursModal = $_POST["filtreAutresJours"];
	
	echo "<script>window.location.href='../vue/tableaudebordAdmin.php?bouton=autresjours&filtreAutresJoursModal=$filtreAutresJoursModal';</script>"; exit;
}

if(isset($_POST["joursReservationsID"])) {
	$joursReservationsID = $_POST["joursReservationsID"];
	ajax_horairesReservations($pdo, $joursReservationsID);
}

if(isset($_POST["joursReservationsASupprimerID"])) {
	$joursReservationsASupprimerID = $_POST["joursReservationsASupprimerID"];
	ajax_ReservationsASupprimer($pdo, $joursReservationsASupprimerID);
}

if(isset($_POST["joursReservationsID2"])) {
	$joursReservationsID2 = $_POST["joursReservationsID2"];
	ajax_horairesReservations2($pdo, $joursReservationsID2);
}

if(isset($_POST["horairesReservations2ID"])) {
	$horairesReservations2ID = $_POST["horairesReservations2ID"];
	ajax_typesPrecision($pdo, $horairesReservations2ID);
}

if(isset($_POST["id_utilisateur_infosID0"])) {
	$id_utilisateur_infosID0 = $_POST["id_utilisateur_infosID0"];
	ajax_informationsClient0($pdo, $id_utilisateur_infosID0);
}
if(isset($_POST["id_utilisateur_infosID1"])) {
	$id_utilisateur_infosID1 = $_POST["id_utilisateur_infosID1"];
	ajax_informationsClient1($pdo, $id_utilisateur_infosID1);
}
if(isset($_POST["id_utilisateur_infosID2"])) {
	$id_utilisateur_infosID2 = $_POST["id_utilisateur_infosID2"];
	ajax_informationsClient2($pdo, $id_utilisateur_infosID2);
}
if(isset($_POST["id_utilisateur_infosID3"])) {
	$id_utilisateur_infosID3 = $_POST["id_utilisateur_infosID3"];
	ajax_informationsClient3($pdo, $id_utilisateur_infosID3);
}

if(isset($_POST["id_utilisateur_resaID"])) {
	$id_utilisateur_resaID = $_POST["id_utilisateur_resaID"];
	ajax_reservationsClient($pdo, $id_utilisateur_resaID);
}
?>