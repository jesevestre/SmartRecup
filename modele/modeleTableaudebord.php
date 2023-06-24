<?php
function editerProfil($pdo, $prenom, $nom, $genre, $telephone, $email) {
    $sql = "UPDATE Utilisateurs SET prenom = ?, nom = ?, genre = ?, telephone = ? WHERE email = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($prenom, $nom, $genre, $telephone, $email));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function reserverRendezvous($pdo, $id_utilisateur, $id_reservation) {
    $sql = "UPDATE Reservations SET id_utilisateur = ?, id_etat = 2 WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur, $id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function ajoutRendezvous($pdo, $type, $dateTime) {
    $sql = "INSERT INTO Reservations (id_utilisateur, id_type, id_etat, date, commentaire) VALUES (NULL, ?, 1, ?, NULL)";    
    $req = $pdo->prepare($sql);
    $req->execute(array($type, $dateTime));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function supprimerRendezvous($pdo, $id_reservation) {
    $sql = "DELETE FROM Reservations WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function retirerRendezvous($pdo, $id_reservation) {
    $sql = "UPDATE Reservations SET id_utilisateur = NULL, id_etat = 1, commentaire = NULL WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function editCommentaireRdv($pdo, $commentaire, $id_reservation) {
    $sql = "UPDATE Reservations SET commentaire = ? WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($commentaire, $id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}
?>