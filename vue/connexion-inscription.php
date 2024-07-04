<?php session_start(); ?>
<?php include("../contenu/connexion/BDDIntersection.php"); ?>
<?php require("../contenu/recaptcha/autoload.php"); ?>
<?php include "entetedepage.php"; ?>

<?php
if(!empty($_SESSION["email"])) {
    if($_SESSION["admin"] == 1) {
        echo "<script>window.location.href='../vue/tableaudebordAdmin.php';</script>"; exit;
    } else {
        echo "<script>window.location.href='../vue/tableaudebordUti.php';</script>"; exit;
    }
}
?>

<div class="row d-flex justify-content-center mt-5 pb-3">
    <div class="col-4 cadrage verticale_separation">
        <img class="logo-img" src="../contenu/images/Logo_smartrecup_noir_sans_fond2.png" alt="Image du logo de SmartRécup" />
    </div>

    <div class="col-8 cadrage">
        
        <div class="pt-3 mb-3">
            <h3>Connexion</h3>
        </div>

        <!-- Gestion des messages et erreurs -->
            <?php
            // La div des messages à afficher est de base invisible
            $display = "display: none";
            if(isset($_GET["errorConnexion"]) || isset($_GET["successConnexion"])) {
                $error = $_GET["errorConnexion"];
                $success = $_GET["successConnexion"];
                $email = "";
                $email = $_GET["email"];
                // La div des messages à afficher est visible
                $display = "display: block";

                if($error == "errorConnexion") {
                    $messageConnexion = "Votre adresse email ou votre mot de passe sont incorrects.";
                    $icone = "fa fa-exclamation-triangle";
                    $color = "alert-warning";
                } else if($error == "error2Connexion") {
                    $messageConnexion = "Assurez-vous bien que votre Email et votre mot de passe soient renseignés.";
                    $icone = "fa fa-exclamation-triangle";
                    $color = "alert-warning";
                } else if($error == "error3Connexion") {
                    $messageConnexion = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <a href='mailto:contact@smartrécup.fr'>contact@smartrécup.fr</a>";
                    $icone = "fa fa-exclamation-triangle";
                    $color = "alert-warning";
                } else if($error == "error4Connexion") {
                    $messageConnexion = "<b>Votre compte a été desactivé.</b> Si c'est une erreur, veuillez contacter le support technique à l'adresse <a href='mailto:contact@smartrécup.fr'>contact@smartrécup.fr</a>";
                    $icone = "fa fa-exclamation-triangle";
                    $color = "alert-warning";
                } else if($success == "successConnexion") {
                    $messageConnexion = "<b>Félicitation pour votre inscription !</b> Un email vient de vous être envoyé pour vous permettre de vous connecter à votre espace personnel.";
                    $icone = "fa-solid fa-check";
                    $color = "alert-success";
                } else if($success == "confirmationEnvoiEmail") {
                    $messageConnexion = "<b>Un email vient de vous être envoyé à l'adresse email ci-dessous</b>. Vous devez y accéder pour vous connecter.";
                    $icone = "fa-solid fa-check";
                    $color = "alert-success";
                }
            }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
        <i class="<?= $icone ?>"></i> <?= $messageConnexion; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurConnexion.php" method="post" id="connexion">
            <div class="mb-3">
                <input type="email" name="email" id="emailConnexion" class="form-control" placeholder="Adresse email" <?php if($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>

            <div class="mb-3">
                <input type="password" name="mdp" id="mdpConnexion" class="form-control" placeholder="Mot de passe" required>
                <div class="centrer"><a href="mot-de-passe-oublie.php">Mot de passe oublié ?</a></div>
            </div>

            <div class="pt-4 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../" type="button" id="retour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</i></a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" name="connexion" id="BtnConnexion" class="btn btn-secondary col-10 mx-auto">Se connecter <i class="fa-sharp fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </form>

        <hr class="horizontale_separation mt-4">

        <div class="pt-3 mb-3">
            <h3>Créer un compte</h3>
        </div>

        <!-- Gestion des messages et erreurs -->
        <?php
        // La div des messages à afficher est de base invisible
        $display = "display: none";
        if(isset($_GET["errorInscription"])) {
            $error = $_GET["errorInscription"];
            $prenom = "";
            $prenom = $_GET['prenom'];
            $nom = "";
            $nom = $_GET['nom'];
            $email = "";
            $email = htmlentities($_GET['email']);
            $telephone = $_GET['telephone'];
            $cgu = $_GET['cgu'];
            // La div des messages à afficher est visible
            $display = "display: block";

            if($error == "errorInscription") {
                $message = "L'adresse email est déjà utilisée.";
                $color = "alert-warning";
            } else if($error == "error2Inscription") {
                $message = "Assurez-vous bien que tous les champs obligatoires soient renseignés.";
                $color = "alert-warning";
            } else if($error == "error3Inscription") {
                $message = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <a href='mailto:contact@smartrécup.fr'>contact@smartrécup.fr</a>";
                $color = "alert-danger";
            } else if($error == "error4Inscription") {
                $message = "Le consentement des Conditions Générales d'Utilisation (CGU) sont obligatoires.</i>";
                $color = "alert-warning";
            } else if($error == "error5Inscription") {
                $message = "Vous devez valider le reCaptcha.</i>";
                $color = "alert-warning";
            }
        }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
            <i class="fa fa-exclamation-triangle"></i> <?= $message; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurInscription.php" method="post" id="inscription">
            
            <div class="mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Votre adresse email" <?php if($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Votre prénom" onChange="this.value=premierCaractereMaj(this.value);" <?php if($prenom != "") { ?> value="<?= $prenom; ?>" <?php } ?> required>
                </div>

                <div class="col-6">
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" onkeyup="this.value=this.value.toUpperCase()" <?php if($nom != "") { ?> value="<?= $nom; ?>" <?php } ?> required>
                </div>
            </div>

            <div id="telephone" class="row mb-3">
                <div class="col-4 col-sm-3 col-md-4 col-lg-3 mx-auto">
                    <select class="form-control" name="indicatif_tel">
                        <option value="+33">N° français</option>
                        <option value="+32">N° belge</option>
                        <option value="+41">N° suisse</option>
                        <option value="+352">N° lux</option>
                    </select>
                    <small class="text-muted">Type de numéro</small>
                </div>
                <div class="col-8 col-sm-9 col-md-8 col-lg-9 mx-auto">
                    <input type="tel" name="telephone" class="form-control" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder="Votre numéro de téléphone" <?php if($telephone != "") { ?> value="<?= $telephone; ?>" <?php } ?>>
                    <small class="text-muted">Champ facultatif</small>
                </div>
            </div>

            <div class="mb-4">
                <div>
                    <legend class="col-10 legendeCgu">J'accepte les <a href="mentions-legales.php">Conditions Générales d'Utilisation</a> : </legend>
                    <input type="checkbox" name="cgu" id="cgu" class="col-2 form-check-input legendeCguInput" value="1" onclick="debloquerSinscrire()" required>
                </div>
            </div>

            <div class="legendeCaptcha mb-3">
                <div class="g-recaptcha" data-sitekey="6LcilIcpAAAAAJ1n-hprVbOwKm_jsuzFnendK_ui"></div>
                <!-- Pour SameSport -->
                <!-- <div class="g-recaptcha" data-sitekey="6Ldu28YpAAAAAH2tnroieIhssHx7aVEbuDqFpNGg"></div> -->
            </div>

            <div class="pt-4 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../" type="button" id="retour2" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</i></a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" name="inscription" id="BtnInscription" class="btn btn-secondary col-10 mx-auto disabled" onClick="clickContact()">S'inscrire <i class="fa-sharp fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>

<script src="../contenu/js/inscription.js"></script>

<!-- Validation du ReCaptcha pour l'inscription -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<br />
<br />
<br />
<?php include "pieddepage.php"; ?>