<header>
    <a href="/Home" class="logo">
        <img src="/assets/ico/Rekey2.webp" alt="Logo ReKey" />
    </a>
    
    <div class="nav-center-capsule">
        <nav class="platform-nav">
            <a href="/Home?platform=1#annonces" class="nav-link">
                <img src="/assets/ico/monitor.svg" class="nav-icon" />
                <span>PC</span>
            </a>
            <a href="/Home?platform=2#annonces" class="nav-link">
                <img src="/assets/ico/playstation.svg" class="nav-icon" />
                <span>PlayStation</span>
            </a>
            <a href="/Home?platform=3#annonces" class="nav-link">
                <img src="/assets/ico/xbox.svg" class="nav-icon" />
                <span>Xbox</span>
            </a>
            <a href="/Home?platform=4#annonces" class="nav-link">
                <img src="/assets/ico/nintendo.svg" class="nav-icon" />
                <span>Nintendo</span>
            </a>
        </nav>

        <form class="search-form-expanded" action="/Home#annonces" method="GET">
            <input
                type="text"
                name="q"
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
        <div class="desktop-user-menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-dropdown">
                    <a href="#" aria-label="Mon Compte" class="dropdown-toggle" style="display: flex; align-items: center; gap: 0.5rem;">
                        <img src="<?= $_SESSION['user_avatar'] ?? '/assets/ico/user.svg' ?>" alt="Profil" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; filter: none !important;" />
                        <span class="desktop-pseudo" style="font-size: 0.9rem; font-weight: 600; color: var(--cyan);"><?= htmlspecialchars($_SESSION['user_pseudo']) ?></span>
                    </a>

                    <ul class="dropdown-menu">
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <li><a href="/Admin/dashboard" style="color: var(--rose);">Administration</a></li>
                        <?php endif; ?>

                        <li><a href="/Profile">Mon Profil</a></li>
                        <li><a href="/Ad/mine">Mes Annonces</a></li>
                        <li><a href="/Order/myPurchases">Mes Achats</a></li>
                        <li><a href="/Favorites">Mes Favoris</a></li>
                        <li class="logout-link"><a href="/Auth/logout">Déconnexion</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/Auth/login" aria-label="Se connecter" style="display: flex; align-items: center;">
                    <img src="/assets/ico/user.svg" alt="Se connecter" />
                </a>
            <?php endif; ?>
        </div>

        <button class="hamburger-btn" aria-label="Ouvrir le menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </nav>
</header>

<div class="mobile-overlay">
    <button class="close-mobile-menu">✕</button>
    
    <div class="mobile-menu-content">
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mobile-user-section">
                <div class="mobile-avatar-placeholder" style="overflow: hidden; padding: 0;">
                    <img src="<?= $_SESSION['user_avatar'] ?? '/assets/ico/user.svg' ?>" alt="Profil" style="width: 100%; height: 100%; object-fit: cover; filter: none !important;" />
                </div>
                <span class="mobile-pseudo"><?= htmlspecialchars($_SESSION['user_pseudo']) ?></span>
            </div>
            
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="/Admin/dashboard" class="mobile-nav-link secondary" style="color: var(--rose);">Administration</a>
            <?php endif; ?>
            
            <a href="/Profile" class="mobile-nav-link secondary">Mon Terminal</a>
            <a href="/Ad/mine" class="mobile-nav-link secondary">Mes Ventes</a>
            <a href="/Order/myPurchases" class="mobile-nav-link secondary">Mes Achats</a>
            
        <?php else: ?>
            <a href="/Auth/login" class="mobile-nav-link secondary text-cyan">Se connecter</a>
            <a href="/Auth/register" class="mobile-nav-link secondary">S'inscrire</a>
        <?php endif; ?>

        <hr class="mobile-divider">

        <a href="/Home?platform=1#annonces" class="mobile-nav-link">
            <img src="/assets/ico/monitor.svg" alt="PC" /> PC
        </a>
        <a href="/Home?platform=2#annonces" class="mobile-nav-link">
            <img src="/assets/ico/playstation.svg" alt="PlayStation" /> PlayStation
        </a>
        <a href="/Home?platform=3#annonces" class="mobile-nav-link">
            <img src="/assets/ico/xbox.svg" alt="Xbox" /> Xbox
        </a>
        <a href="/Home?platform=4#annonces" class="mobile-nav-link">
            <img src="/assets/ico/nintendo.svg" alt="Nintendo" /> Nintendo
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <hr class="mobile-divider">
            <a href="/Auth/logout" class="mobile-nav-link secondary text-danger">Déconnexion</a>
        <?php endif; ?>
    </div>
</div>