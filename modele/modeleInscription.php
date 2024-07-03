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

function ajoutUtilisateur($pdo, $prenom, $nom, $email, $motdepasseEncode, $mdpTemp, $indicatif_tel, $telephone, $cgu) {
    $sql = "INSERT INTO Utilisateurs(prenom, nom, email, motdepasse, mdp_temp, indicatif_tel, telephone, mentions_legales, date_inscription) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())";    
    $req = $pdo->prepare($sql);
    $req->execute(array($prenom, $nom, $email, $motdepasseEncode, $mdpTemp, $indicatif_tel, $telephone, $cgu));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}
?>