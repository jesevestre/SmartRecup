<?php require("../contenu/recaptcha/autoload.php"); ?>
<?php include("../service/serviceEnvoiEmail.php"); ?>

<?php
if(isset($_POST["contact"])) {
    $email = htmlentities($_POST["email"]);
	$prenom = $_POST["prenom"];
	$prenom = str_replace( array( "%", "@", '\'', '"', ";", "<", ">" ), " ", $prenom);
	$nom = $_POST["nom"];
	$nom = str_replace( array( "%", "@", '\'', '"', ";", "<", ">" ), " ", $nom);
    $titre = $_POST['titre'];
	$message = $_POST['message'];

	if(!empty($_POST["g-recaptcha-response"])) {
		$recaptcha = new \ReCaptcha\ReCaptcha("6LcilIcpAAAAALT2M2ToWMBYxftemduazzzk16bD");
		$resp = $recaptcha->verify($_POST["g-recaptcha-response"]);
		if($resp->isSuccess()) {
			$recaptchaVerifie = true;
		} else {
			echo "<script>window.location.href='../vue/contact.php?errorContact=error2&email=' + '$email' + '&prenom=' + '$prenom' + '&nom=' + '$nom' + '&titre=' + '$titre' + '&message=' + '$message';</script>";exit;
		} 
	} else {
		echo "<script>window.location.href='../vue/contact.php?errorContact=error3&email=' + '$email' + '&prenom=' + '$prenom' + '&nom=' + '$nom' + '&titre=' + '$titre' + '&message=' + '$message';</script>"; exit;
	}

	if(!empty($email) && !empty($prenom) && !empty($nom) && !empty($titre) && !empty($message)) {
		$result = envoi_email_contact($email, $prenom, $nom, $titre, $message);

		echo "<script>window.location.href='../vue/contact.php?successContact=success';</script>"; exit;

	} else {
		echo "<script>window.location.href='../vue/contact.php?errorContact=error&email=' + '$email' + '&prenom=' + '$prenom' + '&nom=' + '$nom' + '&titre=' + '$titre' + '&message=' + '$message';</script>"; exit;
	}

} else {
	echo "<script>window.location.href='../vue/contact.php?errorContact=error2&email=' + '$email' + '&prenom=' + '$prenom' + '&nom=' + '$nom' + '&titre=' + '$titre' + '&message=' + '$message';</script>"; exit;
}
?>