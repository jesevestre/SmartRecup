<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleMdpOublie.php"); ?>
<?php require("../contenu/recaptcha/autoload.php"); ?>
<?php include("../service/serviceEnvoiEmail.php"); ?>

<?php
if(isset($_POST["MdpOublie"])) {
	$email = htmlentities($_POST["email"]);

	if(!empty($_POST["g-recaptcha-response"])) {
		$recaptcha = new \ReCaptcha\ReCaptcha("6LcilIcpAAAAALT2M2ToWMBYxftemduazzzk16bD");
		$resp = $recaptcha->verify($_POST["g-recaptcha-response"]);

		if($resp->isSuccess()) {
			$recaptchaVerifie = true;
		} else {
			echo "<script>window.location.href='../vue/mot-de-passe-oublie.php?error=error4&email=' + '$email';</script>"; exit;
		} 
	} else {
		echo "<script>window.location.href='../vue/mot-de-passe-oublie.php?error=error2&email=' + '$email';</script>"; exit;
	}

	if(!empty($email)) {
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
			nouveauMdp($pdo, $email, $motdepasseEncode, $mdpTemp);
			envoi_email_mdp_oublie_client($pdo, $email, $mdpTemp);
			echo "<script>window.location.href='../vue/connexion-inscription.php?successConnexion=confirmationEnvoiEmail&email=' + '$email' + '&motdepasse=' + ' ';</script>"; exit;

		} else {
			echo "<script>window.location.href='../vue/mot-de-passe-oublie.php?error=error3&email=' + '$email';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue//mot-de-passe-oublie.php?error=error';</script>"; exit;
	}
} else {
	echo "<script>window.location.href='../vue//mot-de-passe-oublie.php?error=error4';</script>"; exit;
}
?>