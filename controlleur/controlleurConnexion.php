<?php session_start(); ?>
<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleConnexion.php"); ?>
<?php require "../service/compteur.php"; ?>

<?php
if(isset($_POST["connexion"])) {
	$email = htmlentities($_POST["email"]);
	$mdp = sha1($_POST["mdp"]);

	if(!empty($email) && !empty($mdp)) {
		$result = verifUtilisateur($pdo, $email, $mdp);

		if($result == true) {
			maj_derniereconnexion($pdo, $email);

			$admin = verifAdmin($pdo, $email);
			if($admin) {
				$_SESSION["email"] = $email;
				$_SESSION["admin"] = $admin;
				echo "<script>window.location.href='../vue/tableaudebordAdmin.php?success=successConnexion';</script>"; exit;
			} else {
				$bloque = verifBloque($pdo, $email);
				if($bloque == false) {
					date_premier_jour_visite();
					ajouter_visite();
					ajouter_visite_today();
					date_today();
					$_SESSION["email"] = $email;
					echo "<script>window.location.href='../vue/tableaudebordUti.php?success=successConnexion';</script>"; exit;
				} else {
					echo "<script>window.location.href='../vue/connexion-inscription.php?errorConnexion=error4Connexion&email=' + '$email';</script>"; exit;
				}
			}

		} else {
			echo "<script>window.location.href='../vue/connexion-inscription.php?errorConnexion=errorConnexion&email=' + '$email';</script>"; exit;
		}

	} else {
		echo "<script>window.location.href='../vue/connexion-inscription.php?errorConnexion=error2Connexion';</script>"; exit;
	}
} else {
	echo "<script>window.location.href='../vue/connexion-inscription.php?errorConnexion=error3Connexion';</script>"; exit;
}
?>