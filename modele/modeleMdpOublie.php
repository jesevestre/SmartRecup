<?php
function verifUtilisateur($pdo, $email) {
    $sql = "SELECT * FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
	$result = $req->rowCount();

    if($result == 1) {
        return true;
    } else {
        return false;
    }
}

function nouveauMdp($pdo, $email, $motdepasseEncode, $mdpTemp) {
$sql = "UPDATE Utilisateurs SET motdepasse = ?, mdp_temp = ? WHERE email = ?"; 
        $req = $pdo->prepare($sql);
        $req->execute(array($motdepasseEncode, $mdpTemp, $email));
        $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}