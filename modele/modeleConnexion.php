
<?php
function verifUtilisateur($pdo, $email, $mdp) {
    $sql = "SELECT * FROM Utilisateurs WHERE email = ? AND motdepasse = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email, $mdp));
	$result = $req->rowCount();

    if($result == 1) {
        return true;
    } else {
        return false;
    }
}
?>