<?php include "entetedepage.php"; ?>

<style>
body {
    background: url('../contenu/images/paysage.jpg');
}
</style>

<div class="row d-flex justify-content-center cadrage3">
    <div class="col-12 col-md-8 col-lg-8 col-xl-6 cadrage2">
        
        <div class="pt-3 mb-3">
            <h1>INSCRIPTION</h1>
        </div>

        <!-- Gestion des messages et erreurs -->
        <?php
        // La div des messages à afficher est de base invisible
        $display = "display: none";
        $genre = "1";
        if (isset($_GET["error"])) {
            $error = $_GET["error"];
            $prenom = "";
            $prenom = $_GET['prenom'];
            $nom = "";
            $nom = $_GET['nom'];
            $genre = $_GET['genre'];
            $email = "";
            $email = htmlentities($_GET['email']);
            $telephone = $_GET['telephone'];
            // La div des messages à afficher est visible
            $display = "display: block";

            if ($error == "error") {
                $message = "L'adresse Email est déjà utilisée, veuillez en choisir une autre.";
                $color = "alert-warning";
            } else if ($error == "error2") {
                $message = "Assurez-vous bien que tous les champs obligatoires soient renseignés.";
                $color = "alert-warning";
            } else if ($error == "error3") {
                $message = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <i><b>sevestre.jb@gmail.com</b></i>";
                $color = "alert-danger";
            }
        }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert  <?= $color; ?> text-center small">
            <i class="fa fa-exclamation-triangle"></i> <?= $message; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurInscription.php" method="post">

            <div class="mb-3">
                <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" onChange="this.value=premierCaractereMaj(this.value);" <?php if ($prenom != "") { ?> value="<?= $prenom; ?>" <?php } ?> required>
            </div>

            <div class="mb-3">
                <input type="text" name="nom" class="form-control" placeholder="Votre nom" onkeyup="this.value=this.value.toUpperCase()" <?php if ($nom != "") { ?> value="<?= $nom; ?>" <?php } ?> required>
            </div>

            <div class="mb-3">
                <div class="d-grid d-flex col-12 mx-auto">
                    <div class="col-4 d-grid mx-auto">
                        <legend class="legendegenre">Votre genre :</legend>
                    </div>
                    <div>
                        <input type="radio" name="genre" value="0" <?php if ($genre == 0) { ?> checked <?php } ?>>
                    </div>
                    <div class="col-3 d-grid mx-auto">
                        <label class="legendegenre" for="0">Femme</label>
                    </div>
                    <div>
                        <input type="radio" name="genre" value="1" <?php if ($genre != "0") { ?> checked <?php } ?>>
                    </div>
                    <div class="col-4 d-grid mx-auto">
                        <label class="legendegenre" for="1">Homme</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Votre adresse mail" <?php if ($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>

            <div class="mb-3">
                <input type="password" id="mdp" name="mdp" class="form-control" placeholder="Votre mot de passe" required>
            </div>

            <div class="mb-3">
                <input type="password" id="mdpRepete" name="mdpRepete" class="form-control" placeholder="Confirmer votre mot de passe" onBlur='verifMdp()' required>
            </div>

            <div id="telephone" class="mb-3 pb-5">
                <input type="tel" name="telephone" class="form-control" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder="Votre numéro de téléphone" <?php if ($telephone != "") { ?> value="<?= $telephone; ?>" <?php } ?>>
            </div>

            <label class="col-12" id="texteErreur">&nbsp;</label>

            <div class="pt-1 mb-3 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../index.php" type="button" class="btn btn-primary col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour accueil</a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" id="inscription" name="inscription" class="btn btn-success col-10 mx-auto disabled">S'inscrire <i class="fa-sharp fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

        </form>

    </div>
</div>
    
<script src="../contenu/js/inscription.js"></script>
<?php include "pieddepage.php"; ?>