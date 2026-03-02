<header>
    <a href="#" class="logo">
        <img src="/assets/ico/Rekey2.webp" alt="Logo ReKey" />
    </a>
    <div class="nav-center-capsule">
        <nav class="platform-nav">
            <a href="#" class="nav-link">
                <img src="/assets/ico/monitor.svg" class="nav-icon" />
                <span>PC</span>
            </a>
            <a href="#" class="nav-link">
                <img src="/assets/ico/playstation.svg" class="nav-icon" />
                <span>PlayStation</span>
            </a>

            <a href="#" class="nav-link">
                <img src="/assets/ico/xbox.svg" class="nav-icon" />
                <span>Xbox</span>
            </a>
            <a href="#" class="nav-link">
                <img src="/assets/ico/nintendo.svg" class="nav-icon" />
                <span>Nintendo</span>
            </a>
        </nav>

        <form class="search-form-expanded" action="#">
            <input
                type="text"
                placeholder="Minecraft, RPG, Cyberpunk..."
                id="search-input" />

            <span class="close-search">✕</span>
        </form>

        <button
            class="search-btn-round"
            aria-label="Ouvrir la recherche"
            id="toggle-search">
            <img src="/assets/ico/search.svg" alt="Rechercher" />
        </button>
    </div>

    <nav class="user-nav">
        <a href="#" aria-label="Mon Panier">
            <img src="/assets/ico/cart.svg" alt="Panier" />
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-dropdown">
                <a href="#" aria-label="Mon Compte" class="dropdown-toggle" style="display: flex; align-items: center; gap: 0.5rem;">
                    <img src="/assets/ico/user.svg" alt="Profil" />
                    <span style="font-size: 0.9rem; font-weight: 600; color: var(--cyan);"><?= htmlspecialchars($_SESSION['user_pseudo']) ?></span>
                </a>

                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <li><a href="/Admin" style="color: var(--rose);">Administration</a></li>
                    <?php endif; ?>

                    <li><a href="/Profile">Mon Profil</a></li>
                    <li><a href="/Ads/myAds">Mes Annonces</a></li>
                    <li><a href="/Orders">Mes Achats</a></li>
                    <li><a href="/Favorites">Mes Favoris</a></li>
                    <li class="logout-link"><a href="/Auth/logout">Déconnexion</a></li>
                </ul>
            </div>

        <?php else: ?>
            <a href="/Auth/login" aria-label="Se connecter">
                <img src="/assets/ico/user.svg" alt="Se connecter" />
            </a>
        <?php endif; ?>
    </nav>
</header>