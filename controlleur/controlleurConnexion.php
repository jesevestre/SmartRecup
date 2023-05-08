<?php session_start(); ?>
<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleConnexion.php"); ?>

<?php
if(isset($_POST['connexion'])) {
	$email = htmlentities($_POST['email']);
	$mdp = sha1($_POST['mdp']);
	$_SESSION['email'] = $email;

	if(!empty($email) && !empty($mdp)) {

		$result = verifUtilisateur($pdo, $email, $mdp);

		if($result == true) {
			$_SESSION["email"] = $email;
			echo "<script>window.location.href='../vue/tableaudebord.php?success=success';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/connexion.php?error=error&email=' + '$email';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/connexion.php?error=error2';</script>"; exit;
	}
} else {
	echo "<script>window.location.href='../vue/connexion.php?error=error3';</script>"; exit;
}
?>