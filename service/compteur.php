<?php
function date_premier_jour_visite() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDatePremierJour";
    $dateJour = date("d/m/Y");
    
    file_put_contents($fichier, $dateJour);
}

function date_premier_jour_visite_sortie() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDatePremierJour";

    return file_get_contents(($fichier));
}

## ----------------------------------------------------------------------------- ##

function date_today() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDateToday";
    $dateJour = date("Y-m-d");

    if(file_exists($fichier)) {
        file_put_contents($fichier, $dateJour);
    } else {
        file_put_contents($fichier, "0000-00-00");
    }
}

function date_today_sortie() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDateToday";

    return file_get_contents(($fichier));
}

## ----------------------------------------------------------------------------- ##

function ajouter_visite_today() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDataToday";
    $dateJour = date("Y-m-d");

    if(file_exists($fichier)) {

        if($dateJour == date_today_sortie()) {
            $compteur = (int)file_get_contents($fichier);
            $compteur++;
            file_put_contents($fichier, $compteur);
        } else {
            file_put_contents($fichier, "1");
        }
    } else {
        file_put_contents($fichier, "0");
    }
}

function nombre_visites_today() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurDataToday";

    return file_get_contents(($fichier));
}

## ----------------------------------------------------------------------------- ##

function ajouter_visite() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurData";

    if(file_exists($fichier)) {
        $compteur = (int)file_get_contents($fichier);
        $compteur++;
        file_put_contents($fichier, $compteur);
    } else {
        file_put_contents($fichier, "0");
    }
}

function nombre_visites() {
    $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . "service" . DIRECTORY_SEPARATOR . "compteurData";

    return file_get_contents(($fichier));
}