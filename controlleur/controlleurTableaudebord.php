<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleTableaudebord.php"); ?>

<?php
if(isset($_POST['action']) && $_POST['action'] == "editProfilModal") {
    $email = $_POST['email'];
	$prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $genre = $_POST['genre'];
    $telephone = $_POST['telephone'];

	if(!empty($prenom) && !empty($nom)) {
		$result = editerProfil($pdo, $prenom, $nom, $genre, $telephone, $email);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success2';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST['action']) && $_POST['action'] == "prendreRendezvousModal") {
    $id_utilisateur = $_POST['id_utilisateur'];
	$id_reservation = $_POST['id_reservation'];

	if(!empty($id_utilisateur) && !empty($id_reservation)) {
		$result = reserverRendezvous($pdo, $id_utilisateur, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success3';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST['action']) && $_POST['action'] == "ajoutRendezvousModal") {
    $dateTime = $_POST['dateTime'];
	$type = $_POST['type'];

	if(!empty($dateTime) && !empty($type)) {
		$result = ajoutRendezvous($pdo, $type, $dateTime);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success4';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST['action']) && $_POST['action'] == "supprimerRendezvousModal") {
    $id_reservation = $_POST['id_reservation'];

	if(!empty($id_reservation)) {
		$result = supprimerRendezvous($pdo, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success5';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

if(isset($_GET['retirerRendezvousModal'])) {
    $id_reservation = $_GET['retirerRendezvousModal'];

	if(!empty($id_reservation)) {
		$result = retirerRendezvous($pdo, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success6';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST['action']) && $_POST['action'] == "editCommentaireModal") {
    $id_reservation = $_POST['id_reservation'];
	$commentaire = $_POST['commentaire'];

	if(!empty($id_reservation)) {
		$result = editCommentaireRdv($pdo, $commentaire, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success7';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebord.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebord.php?error=error2';</script>"; exit;
	}
}

?>