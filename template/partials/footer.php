<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="/Home" class="footer-logo">
                <img src="/assets/ico/Rekey2.webp" alt="Logo ReKey" />
            </a>
            <p class="footer-desc">La plateforme nouvelle génération pour acheter et vendre vos clés de jeux vidéo instantanément et au meilleur prix.</p>
        </div>

        <div class="footer-links">
            <h4>Navigation</h4>
            <ul>
                <li><a href="/Home#annonces">Catalogue</a></li>
                <li><a href="/Ad/add">Vendre une clé</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="/Auth/register">Créer un compte</a></li>
                <?php else: ?>
                    <li><a href="/Profile">Mon Profil</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-links">
            <h4>Support & Légal</h4>
            <ul>
                <li><a href="#">FAQ & Aide</a></li>
                <li><a href="#">Nous contacter</a></li>
                <li><a href="/Legal/cgv">Conditions générales (CGV)</a></li>
                <li><a href="/Legal/privacy">Politique de confidentialité</a></li>
            </ul>
        </div>

        <div class="footer-social">
            <h4>Rejoignez-nous</h4>
            <div class="social-icons">
                <a href="#" aria-label="X">
                    <img src="/assets/ico/x.svg" alt="X" />
                </a>
                <a href="#" aria-label="Discord">
                    <img src="/assets/ico/discord.svg" alt="Discord" />
                </a>
                <a href="#" aria-label="Twitch">
                    <img src="/assets/ico/twitch.svg" alt="Twitch" />
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> ReKey. Tous droits réservés.</p>
    </div>
</footer>