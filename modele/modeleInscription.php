<?php
function ajoutUtilisateur($pdo, $prenom, $nom, $genre, $email, $motdepasse, $telephone) {
    $sql = "SELECT * FROM Utilisateurs WHERE email = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
	$result = $req->rowCount();

    if($result == 0) {
        $sql = "INSERT INTO Utilisateurs(prenom, nom, genre, email, motdepasse, telephone, administrateur) VALUES(?, ?, ?, ?, ?, ?, 0)";    
        $req = $pdo->prepare($sql);
        $req->execute(array($prenom, $nom, $genre, $email, $motdepasse, $telephone));
        $result = $req;

        if($result) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>