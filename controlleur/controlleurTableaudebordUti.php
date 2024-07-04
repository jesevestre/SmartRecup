<?php session_start(); ?>
<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleTableaudebordUti.php"); ?>
<?php include("../service/serviceEnvoiEmail.php"); ?>

<?php
if(isset($_POST["action"]) && $_POST["action"] == "editProfilModal") {
    $email = $_POST["email"];
	$prenom = $_POST["prenom"];
	$prenom = str_replace(array("%", "@", "\'", "\"", ";", "<", ">" ), " ", $prenom);
    $nom = $_POST["nom"];
	$nom = str_replace(array("%", "@", "\'", "\"", ";", "<", ">" ), " ", $nom);
    $telephone = $_POST["telephone"];

	if(!empty($prenom) && !empty($nom)) {
		$result = editerProfil($pdo, $prenom, $nom, (!empty($telephone) ? $telephone : NULL), $email);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?success=success2';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "prendreRendezvousModal") {
    $id_utilisateur = $_POST["id_utilisateur"];
	$id_reservation = $_POST["id_reservation"];
	$id_type_massage = $_POST["id_type_massage"];
	$id_type_machine = $_POST["id_type_machine"];
	$option_email_priseRdv_prAdmin = $_POST["option_email_priseRdv_prAdmin"];

	if(!empty($id_utilisateur) && !empty($id_reservation)) {
		$result = reserverRendezvous($pdo, $id_utilisateur, $id_reservation, $id_type_massage, $id_type_machine);
		envoi_email_confirmation_client($pdo, $id_utilisateur, $id_reservation);

		if($option_email_priseRdv_prAdmin == 1) {
			envoi_email_confirmation_admins($pdo, $id_utilisateur, $id_reservation);
		}

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?success=success3';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error2';</script>"; exit;
	}
}

if(isset($_GET["retirezRendezvousModal"])) {
	$id_utilisateur = $_GET["id_utilisateur"];
    $id_reservation = $_GET["retirezRendezvousModal"];
	$option_email_retirezRdv_prAdmin = $_GET["option_email_retirezRdv_prAdmin"];

	if(!empty($id_reservation)) {
		envoi_email_annulation_client($pdo, $id_utilisateur, $id_reservation);

		if($option_email_retirezRdv_prAdmin == 1) {
			envoi_email_annulation_admins($pdo, $id_utilisateur, $id_reservation);
		}

		$result = retirezRendezvous($pdo, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?success=success4';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "editCommentaireClientModal") {
    $id_reservation = $_POST["id_reservation"];
	$commentaireClient = $_POST["commentaireClient"];
	$commentaireClient = str_replace(array("%", "@", "\'", "\"", ";", "<", ">" ), " ", $commentaireClient);

	if(!empty($id_reservation)) {
		$result = editCommentaire($pdo, $commentaireClient, $id_reservation);

		if($result == true) {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?success=success5';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error2';</script>"; exit;
	}
}

if(isset($_POST["action"]) && $_POST["action"] == "editPwdModal") {
	$id_utilisateur = $_POST["id_utilisateur"];
    $mdp = $_POST["mdp"];
	$newMdp = $_POST["newMdp"];
	$repeteMdp = $_POST["repeteMdp"];

	if(!empty($mdp) && !empty($newMdp) && !empty($repeteMdp)) {
		$resultVerifMdp = verificationMdpActuel($pdo, $id_utilisateur, $mdp);
		if($resultVerifMdp) {
			$result = editerMdp($pdo, $id_utilisateur, $newMdp);
			envoi_email_nouveau_mdp_client($pdo, $id_utilisateur);

			if($result == true) {
				echo "<script>window.location.href='../vue/tableaudebordUti.php?success=success6';</script>"; exit;
			} else {
				echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error';</script>"; exit;
			}
		} else {
			echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error3';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/tableaudebordUti.php?error=error2';</script>"; exit;
	}
}

// Liste dynamique pour horaires massage
if(isset($_POST["joursMassageID"])) {
	$joursMassageID = $_POST["joursMassageID"];
	ajax_joursMassage($pdo, $joursMassageID);
}

// Liste dynamique pour horaires machine
if(isset($_POST["joursMachineID"])) {
	$joursMachineID = $_POST["joursMachineID"];
	ajax_joursMachine($pdo, $joursMachineID);
}
?>