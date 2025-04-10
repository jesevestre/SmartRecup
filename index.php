<?php include "vue/entetedepage.php"; ?>
<?php include("contenu/connexion/BDDIntersection.php"); ?>
<link href="contenu/css/styleAccueil.css" rel="stylesheet" />

<?php
$sql = "SELECT *
        FROM Parametrages"; 
$req = $pdo->prepare($sql);
$req->execute();
$parametrages = $req->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container-message">
    <div class="message-div">
        <p class="message-p"><b>Bon à savoir :</b> Le règlement des prestations et des abonnements s'effectue sur place.</p>
    </div>
</div>

<div class="container-entete">
    <div class="logo-div">
        <a href="/vue/connexion-inscription.php"><img src="contenu/images/Logo_smartrecup_noir_sans_fond.png" class="logo" alt="Image représentant le logo de la société" /></a>
    </div>

    <div class="titre-div">
        <h1>SmartRécup</h1>
    </div>

    <ul class="medias-div">
        <li class="bulle"><a href="https://instagram.com/smartrecup" target="_blank"><i class="fab fa-2x fa-instagram-square" alt="Lien pour accéder à Instagram" class="medias"></i></a></li>
        <li class="bulle"><a onClick="ouvrirModaleSnapchat()"><i class="fa-brands fa-2x fa-square-snapchat" alt="Lien pour accéder à Snapshat" class="medias"></i></a></li>
        <li class="bulle"><a href="vue/contact.php"><i class="fas fa-2x fa-envelope" alt="Lien pour prendre contact" class="medias"></i></a></li>
    </ul>

    <div class="menu-div">
        <a href="vue/prestations.php" class="bouton-menu sous-ligne">Prestations</a> | 
        <a href="vue/praticien.php" class="bouton-menu">Praticien</a> | 
        <a href="vue/infos_rdv_faq.php" class="bouton-menu">FAQ/Infos&nbsp;rdv</a> | 
        <a href="vue/contre-indications.php" class="bouton-menu">Contre-indications</a> | 
        <a href="vue/tarification.php" class="bouton-menu">Tarifs</a>
    </div>
</div>

<div class="container-corps">
    <div class="landscape-div">
        <img class="landscape pour_immense_taille" src="contenu/images/imageIndex3.png" alt="Personnne en train de se faire masser" />
        <img class="landscape pour_grande_taille" src="contenu/images/imageIndex2.png" alt="Personnne en train de se faire masser" />
        <img class="landscape pour_petite_taille" src="contenu/images/imageIndex.jpeg" alt="Personnne en train de se faire masser" />
    </div>
    <div id="slogan-div" class="slogan-div">
        <h2 class="slogan-h2">Centre de préparation<br /> et récupération</h2>
        <a class="slogan-button" href='<?= ($parametrages[5]->actif == 1 ? "vue/connexion-inscription.php" : "https://www.planity.com/smart-recup-59650-villeneuve-dascq"); ?>' class="button button--secondary">Rendez-vous</a>
    </div>
</div>

<div class="container-second">
    <h3>Formules abonnements</h3>
    <h4>Retrouvez tous les packs disponibles :</h4>

    <div class="row">
        <div class="col-6">
            <a href="vue/tarification.php"  class="a_titre_et_texte_accueil">
                <img class="photo-img-accueil" src="contenu/images/imageMassageVentouses2.jpg" alt="Image pour le pack 1" />
                <h4 class="titre_h4_accueil">Pack 1 - 80€</h4>
                <p class="texte_p">Deux séances d'1h comprenant un massage au choix + pressothérapie ou cryothérapie compressive.</p>
            </a>
        </div>
        <div class="col-6">
            <a href="vue/tarification.php" class="a_titre_et_texte_accueil">
                <img class="photo-img-accueil" src="contenu/images/imageMassageSuedois.jpg" alt="Image pour le pack 2" />
                <h4 class="titre_h4_accueil">Pack 2 - 120€</h4>
                <p class="texte_p">Trois séances d'1h de massage, soit une séance offerte.</p>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <a href="vue/tarification.php" class="a_titre_et_texte_accueil">
                <img class="photo-img-accueil" src="contenu/images/imageMassageSportif2.jpg" alt="Image pour le pack 3" />
                <h4 class="titre_h4_accueil">Pack 3 - 160€</h4>
                <p class="texte_p">Quatre séances d'1h au choix, soit une séance et demie offerte.</p>
            </a>
        </div>
    </div>
</div>

<hr class="horizontale_separation mt-2 mb-4">

<div id="praticien" class="container-second mb-4">
    <h3>Le praticien</h3>

    <div class="row">
        <div class="col-md-6 pt-3">
            <p class="justifie">Maxime Thiel, footballeur professionnel, a décidé de se lancer dans le domaine du massage bien-être et sportif mettant ainsi sa passion au service des autres. Pour en savoir plus et accéder à ses diplômes et qualifications, cliquez <a href="vue/praticien.php">ici</a>.</p>
        </div>
        <div class="col-md-6 pt-3">
            <p class="justifie">Diplômé du centre de formation <a href="https://harmonie-bien-etre.fr/" target="_blank">Harmonie Bien-être</a> et <a href="https://colibrima.fr/" target="_blank">Colibri Massage Academy</a>, ce praticien aux mains d'or vous invite à profiter de ses compétences pour vous détendre et développer le meilleur de vous-même dans un cadre agréable, situé en face du parc du Hérons.</p>
        </div>
    </div>
</div>

<hr class="horizontale_separation mt-2 mb-4">

<div id="praticien" class="container-second mb-4">
<<<<<<< HEAD
    <h3>Ce site web</h3>

    <div class="row">
        <div class="col-md-6 pt-3">
            <p class="justifie">Vous cherchez un développeur web ou un webmaster avec de l'expérience et une grande rigueur ? Je m'appelle Jean-Baptiste, je m'occupe durant mon temps libre notamment de ce site web.</p>
        </div>
        <div class="col-md-6 pt-3">
            <p class="justifie">J'ai à coeur de développer, améliorer et maintenir des solutions avec un haut niveau de qualité et d'exigence. Je possède une expertise poussée en UX/UI, du responsive et du web design. Si cela vous intéresse, je vous invite à contacter le <a href="tel:+33604460391">06 04 46 03 91</a></p>
=======
    <h3>Développeur et webmaster</h3>

    <div class="row">
        <div class="col-md-6 pt-3">
            <p class="justifie">Vous cherchez un développeur web avec de l'expérience et une grande rigueur ? Je m'appelle Jean-Baptiste, je m'occupe durant mon temps libre notamment de ce site web.</p>
        </div>
        <div class="col-md-6 pt-3">
            <p class="justifie">J'ai à coeur de développer, améliorer et maintenir des solutions avec un haut niveau de qualité et d’exigence. Je possède une expertise poussée en UX/UI, du responsive et du web design. Si cela vous intéresse, je vous invite à consulter ce site web : <a href="http://jsevestre02.free.fr/" target="_blank">http://jsevestre02.free.fr/</a></p>
>>>>>>> 4984eef002bb9c70d36599090d6de2227b06a410
        </div>
    </div>
</div>

<?php
if($parametrages[2]->actif == 1){
?>
    <hr class="horizontale_separation mt-2 mb-4">

    <div class="container-second">
        <h3>Événement</h3>

        <div id="slogan-div" class="slogan-div-evenements">
            <a class="slogan-button" href="vue/evenement.php" style="border-color: #7985B1;" class="button button--secondary"><?php echo $parametrages[3]->champ_texte ?></a>
        </div>

    </div>

    <hr class="horizontale_separation mt-2 mb-4">
<?php
}
?>

<!-- <div class="container-second">
    <h3>Les partenariats</h3>

    <div class="row d-flex justify-content-center pb-5"> -->
        <!-- <div class="col-6 col-sm-5 col-md-4 col-lg-3 pt-3"> -->
            <!-- <a href="https://lestudio44.fr/" class="a_titre_et_texte_accueil" target="_blank"> -->
                <!-- <img class="photo-img-accueil" src="contenu/images/partenariat_Studio_44.png" alt="Patenariat avec Le Studio 44" /> -->
            <!-- </a> -->
        <!-- </div> -->
        <!-- <div class="col-6 col-sm-5 col-md-4 col-lg-3 pt-3">
            <a href="https://www.jcsainghin.com/" class="a_titre_et_texte_accueil" target="_blank">
                <img class="photo-img-accueil" src="contenu/images/partenariat_Judo_Club_Sainghin.png" alt="Patenariat avec le Judo Club Sainghin" />
            </a>
        </div>
    </div>
</div> -->

<script src="contenu/js/index.js"></script>

<?php include "vue/pieddepage.php"; ?>