<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleInscription.php"); ?>
<?php require("../contenu/recaptcha/autoload.php"); ?>
<?php include("../service/serviceEnvoiEmail.php"); ?>

<?php
if(isset($_POST["inscription"])) {
    $prenom = $_POST["prenom"];
	$prenom = str_replace( array( "%", "@", '\'', '"', ";", "<", ">" ), " ", $prenom);
    $nom = $_POST["nom"];
	$nom = str_replace( array( "%", "@", '\'', '"', ";", "<", ">" ), " ", $nom);
	$email = htmlentities($_POST["email"]);
	$indicatif_tel = $_POST["indicatif_tel"];
    $telephone = $_POST["telephone"];
	$cgu = $_POST["cgu"];
	// Choix aléatoire du mot de passe temporaire
	$caracteres = "0123456789abcdefghijklmnopqrstuvwxyz";
	$longueurMax = strlen($caracteres);
	$mdpTemp = "";
	for ($i = 0; $i < 5; $i++)
	{
		$mdpTemp .= $caracteres[rand(0, $longueurMax - 1)];
	}
	$motdepasseEncode = sha1($mdpTemp);

	$result = verifUtilisateur($pdo, $email);
	if($result == true) {
		echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=errorInscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
	}

	if($cgu != 1) {
		echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error4Inscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
	}

	if(!empty($_POST["g-recaptcha-response"])) {
		/* Pour SmartRécup
		$recaptcha = new \ReCaptcha\ReCaptcha("6LcilIcpAAAAALT2M2ToWMBYxftemduazzzk16bD"); */
		/* Pour SameSport */
		$recaptcha = new \ReCaptcha\ReCaptcha("6LcNRQ4rAAAAAISEehZ7w6420Z4etkuZ3hmKKcHA");
		$resp = $recaptcha->verify($_POST["g-recaptcha-response"]);
		
		if($resp->isSuccess()) {
			$recaptchaVerifie = true;
		} else {
			echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error3Inscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone' + ';</script>"; exit;
		} 
	} else {
		echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error5Inscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
	}

	if(!empty($prenom) && !empty($nom) && !empty($email) && !empty($motdepasseEncode) && !empty($mdpTemp) && !empty($indicatif_tel)) {
		$result = ajoutUtilisateur($pdo, $prenom, $nom, $email, $motdepasseEncode, $mdpTemp, $indicatif_tel, (!empty($telephone) ? $telephone : NULL), $cgu);

		envoi_email_inscription_client($prenom, $nom, $email, $mdpTemp);
		envoi_email_inscription_admins($prenom, $nom, $email);

		if($result == true) {
			echo "<script>window.location.href='../vue/connexion-inscription.php?successConnexion=successConnexion&email=' + '$email';</script>";exit;
		} else {
			echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error3Inscription&email=' + '$email';</script>";exit;
		}

	} else {
		echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error2Inscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
	}

} else {
	echo "<script>window.location.href='../vue/connexion-inscription.php?errorInscription=error3Inscription&prenom=' + '$prenom' + '&nom=' + '$nom' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
}
?>