<?php include "entetedepage.php"; ?>

<div class="row d-flex justify-content-center mt-5 pb-5">
        
    <div class="mb-4 cadrage">
        <h3>Infomations rendez-vous et faq</h3>
    </div>

    <div class="cadrage pb-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2530.337429227977!2d3.1457133760724014!3d50.63942417373996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c2d53b7fa333bb%3A0x994c9b4a365db4bd!2sSmartR%C3%A9cup!5e0!3m2!1sfr!2sfr!4v1713098160294!5m2!1sfr!2sfr" style="width: 100%; height: 100%; border:0; padding-top: 15px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="row cadrage">

        <div class="col-sm-6 pt-4">
            <h4>Bon à savoir</h4>
            <div>
                <p class="justifie">Les prestations s'effectuent uniquement avec une <b>visée bien-être</b>, détente, relaxation, soulagement, préparation et récupération. <b>Il ne s'agit pas d'une visée thérapeutique</b>. En conséquence, aucune prestation n'est prise en charge par la sécurité sociale.</p>
                <p class="justifie">Notez qu'une importance particulière à l'écoute, la confidentialité et au professionnalisme sera apportée et mise en place lors de votre séance.</p>
            </div>
        </div>

        <div class="col-sm-6 pt-4">
            <h4>Localisation</h4>
            <div>
                <ul>
                    <li><a href="https://maps.app.goo.gl/myHDAyZ8J9p9rmTq6">5 Avenue de la Créativité, 59650 Villeneuve d'Ascq</a></li>
                    <li><b>Acessible en bus à l'arrêt L.A.M.</b> par la ligne <b>L6</b> et <b>32</b></li>
                    <li>Au première étage à gauche</li>
                    <li>Parking privé <b>gratuit</b></li>
                    <li>Pour les dimanches, seule la barrière côté nord est ouverte.</li>
                </ul>
            </div>
        </div>

        <div class="col-sm-6 pt-4">
            <h4>Comment se passe le réglement ?</h4>
            <div>
                <p class="justifie">Le règlement des prestations et des abonnements s'effectue <b>directement au centre.</b></p>
                <p class="justifie">En cas de désistement, merci de vous désinscrire directement sur le site web de prise de rendez-vous sur votre espace personnel au moins <b>48 heures à l'avance</b> en cliquant <a href="/vue/connexion-inscription.php">ici</a>. Toute réservation non annulée <b>est considérée comme du</b>.</p>
                <p class="justifie">Veuillez noter que tous les paiements doivent être effectués sur place au centre. Nous acceptons les paiements par <b>carte VISA</b> ainsi qu'en <b>espèces</b>. Veuillez noter que nous n'acceptons pas les paiements par chèque.</p><i>Nous vous prions donc de vous assurer d'avoir une carte de paiement valide ou suffisamment de liquide lors de votre visite.</i></p>
            </div>
        </div>

        <div class="col-sm-6 pt-4">
            <h4>Faut-il apporter quelque chose pour la séance ?</h4>
            <div>
                <p class="justifie">Le massage se déroule sur une table avec des crèmes et huiles neutres. Afin de permettre le bon déroulement de la séance et de respecter votre praticien, merci de vous présenter dans une hygiène irréprochable.</p>
                <p class="justifie"><b>Vous n'avez pas de chose particulière à apporter</b>. Votre praticien prendra grand soin de respecter votre intimité en vous couvrant avec des serviettes tout au long de la séance et en s'assurant de votre confort.</p>
            </div>
        </div>

        <div class="col-sm-6 pt-4">
            <h4>J'hésite sur ma prestation ?</h4>
            <div>
                <p class="justifie">En cas de doutes, <b>n'hésitez pas à contacter le praticien</b> par le formulaire de contact accessible en cliquant <a href="/vue/contact.php">ici</a> ou en le contactant directement par téléphone au <a href="tel:+33786542837">0786542837</a>. En cas d'indisponibilité pour vous répondre, veuillez laisser un message en exprimant votre demande pour qu'il puisse vous rappeler.</p>
            </div>
        </div>

        <div class="col-sm-6 pt-4">
            <h4>Vous avez d'autres questions ?</h4>
            <div>
                <p class="justifie">Envoyez les nous pour que nous complétions cette liste. Vous pouvez le faire très simplement avec le formulaire de contact en bas de page ou en cliquant <a href="/vue/contact.php">ici</a>. Nous vous répondrons dans les plus brefs délais.</p>
            </div>
        </div>
    </div>

    <div class="pt-4 mb-3 d-grid d-flex col-12 mx-auto">
        <div class="col-6 d-grid mx-auto">
            <a href="<?= $_GET["retour"] == "tableaudebord" ? "tableaudebordUti.php" : "../"?>" type="button" id="btnRetour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</a>
        </div>
        <div class="col-6 d-grid mx-auto">
            <a href="connexion-inscription.php" type="button" id="btnInscription" class="btn btn-secondary col-10 mx-auto">Rendez-vous <i class="fa-sharp fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

</div>

<script src="../contenu/js/prestations.js"></script>

<?php include "pieddepage.php"; ?>