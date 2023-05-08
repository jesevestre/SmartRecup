<?php
    if(isset($_GET["accepte-cookie"])){
        setcookie("accepte-cookie", "true", time() + 365*24*3600);
        header("Location:./");
        die();
    }
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/PNG" href="contenu/images/cloud.jpg" />
    <title>Well-being with Maxime</title>

    <link href="contenu/css/styleAccueil.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" ref="stylesheet" />

    <script src="https://kit.fontawesome.com/26d4a64054.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="lignes">
        <div class="l1"></div>
        <div class="l2"></div>
    </div>

    <div class="container-first">
        <h1><span class="titre">Well-being </span><span class="titreHidden">with Maxime</span><span class="titreHidden"> </span></h1>

        <div class="container-btns">
            <a href="vue/decouvrir.php"><button class="btn-first b1">To discover</button></a>
            <a href="vue/inscription.php"><button class="btn-first b2">Registration</button></a>
            <a href="vue/connexion.php"><button class="btn-first b3">Connection</button></a>
        </div>
    </div>

    <div class="switch-mode">
        <img src="contenu/images/soleil_clair.png" class="soleil_clair" />
        <img src="contenu/images/soleil_fonce.png" class="soleil_fonce" />
        <input type="checkbox" id="click" onclick="dark()" />
    </div>


    <div>
        <a href="#"><img src="contenu/images/drapeauFR.jpg" class="logo" alt="" /></a>
        <a href="index.php"><img src="contenu/images/drapeauUSA2.png" class="logo" alt="Lien vers la page d'accueil en Français" /></a>
    </div>

    <ul class="medias">
        <li class="bulle"><a href="#" target="_blank"><i class="fab fa-2x fa-facebook" alt="Lien pour accéder à Facebook" style="color: #222" class="logo-medias"></i></a></li>
        <li class="bulle"><a href="#" target="_blank"><i class="fab fa-2x fa-instagram-square" alt="Lien pour accéder à Instagram" style="color: #222" class="logo-medias"></i></a></li>
    </ul>

    <?php
    if(!isset($_COOKIE["accepte-cookie"])){
    ?>
        <div class="banniere">
            <div class="text-banniere">
                <p> This site uses some browser cookies <span class="invisible-banniere-text">to offer you the best possible browsing experience.</span>&nbsp;&nbsp;</p>
            </div>

            <div class="button-banniere-vert">
                <a href="?accepte-cookie">Make&nbsp;disappear</a>
            </div>
            <!-- <span class="invisible-banniere-button">
                <div class="button-banniere-rouge">
                    <a href="?refuse-cookie">Refusé</a>
                </div>
            </span> -->

        </div>
    <?php
    }
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.5.1/gsap.min.js"></script>
    <script src="contenu/js/index.js"></script>
</body>
</html>