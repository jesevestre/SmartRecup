<?php require("../contenu/connexion/BDDIntersection.php"); ?>
<?php include("../modele/modeleInscription.php"); ?>

<?php
if(isset($_POST['inscription'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $genre = $_POST['genre'];
	$email = htmlentities($_POST['email']);
    $motdepasse = sha1($_POST['mdp']);
    $telephone = $_POST['telephone'];

	if(!empty($prenom) && !empty($nom) && !empty($email) && !empty($motdepasse)) {
		$result = ajoutUtilisateur($pdo, $prenom, $nom, $genre, $email, $motdepasse, $telephone);

		if($result == true) {
			echo "<script>window.location.href='../vue/connexion.php?success=success&email=' + '$email';</script>"; exit;
		} else {
			echo "<script>window.location.href='../vue/inscription.php?error=error&prenom=' + '$prenom' + '&nom=' + '$nom' + '&genre=' + '$genre' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
		}
	} else {
		echo "<script>window.location.href='../vue/inscription.php?error=error2&prenom=' + '$prenom' + '&nom=' + '$nom' + '&genre=' + '$genre' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
	}
} else {
	echo "<script>window.location.href='../vue/inscription.php?error=error3&prenom=' + '$prenom' + '&nom=' + '$nom' + '&genre=' + '$genre' + '&email=' + '$email' + '&telephone=' + '$telephone';</script>"; exit;
}
?>