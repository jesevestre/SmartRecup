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

function verifAdmin($pdo, $email) {
    $sql = "SELECT administrateur FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
	$administrateur = $req->fetchAll(PDO::FETCH_OBJ);

    if($administrateur[0]->administrateur == 1) {
        return true;
    } else {
        return false;
    }
}

function verifBloque($pdo, $email) {
    $sql = "SELECT bloque FROM Utilisateurs WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    if($utilisateur[0]->bloque == 0) {
        return false;
    } else {
        return true;
    }
}

function maj_derniereconnexion($pdo, $email) {
    $sql = "UPDATE Utilisateurs SET date_derniere_co = NOW() WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
}
?>