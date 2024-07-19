<?php
function prendreReservation($pdo, $id_reservation, $client, $telephone) {
    $sql = "UPDATE Evenement SET client = ?, telephone = ? WHERE id = ?";
    $req = $pdo->prepare($sql);
    $req->execute(array($client, $telephone, $id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function ajouterReservation($pdo, $time) {
    $sql = "INSERT INTO Evenement (time) VALUES (?)";    
    $req = $pdo->prepare($sql);
    $req->execute(array($time));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function libererReservation($pdo, $id_reservation) {
    $sql = "UPDATE Evenement SET client = NULL, telephone = NULL WHERE id = ?";
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function supprimerReservation($pdo, $id_reservation) {
    $sql = "DELETE FROM Evenement WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}
?>