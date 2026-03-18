<?php
$currentAdminTab = $currentAdminTab ?? 'dashboard';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: -5%; left: 0; opacity: 0.2;"></div>

<section class="admin-page container">
    <div class="admin-wrapper">
        
        <aside class="admin-sidebar">
            <h3>Menu Admin</h3>
            <nav class="admin-nav">
                
                <a href="/Admin/dashboard" class="admin-nav-link <?= $currentAdminTab === 'dashboard' ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Tableau de bord
                </a>
                
                <a href="/Admin/users" class="admin-nav-link <?= $currentAdminTab === 'users' ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Utilisateurs
                </a>
                
                <a href="/Admin/ads" class="admin-nav-link <?= $currentAdminTab === 'ads' ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    Modération
                </a>
                
                <a href="/Admin/categories" class="admin-nav-link <?= $currentAdminTab === 'categories' ? 'active' : '' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                    Catégories & Consoles
                </a>
                
            </nav>
        </aside>

        <main class="admin-content-area">
            <?= $adminContent ?>
        </main>

    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>