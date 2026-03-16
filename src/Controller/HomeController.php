<?php
namespace App\Controller;

use App\Service\AdService;
use \Exception;

/**
 * Contrôleur gérant la page d'accueil (Le Catalogue d'annonces)
 */
class HomeController {

    private AdService $adService;

/**
     * Constructeur avec injection de dépendance
     * @param AdService $adService Le service métier gérant la logique des annonces
     */
    public function __construct(AdService $adService) 
    {
        $this->adService = $adService;
    }

    // =========================================================
    // SECTION : AFFICHAGE DES VUES
    // =========================================================

    /**
     * Affiche la page d'accueil avec toutes les annonces OU les résultats de recherche filtrés
     * URL : /Home ou /Home?q=mot_cle&category=1&platform=2
     */
    public function index(?array $params) {
        try {

            // Récupération des paramètres de recherche dans l'URL (GET)
            $searchQuery = $_GET['q'] ?? '';
            // Mon AdService et mon AdRepository attendent  unentier donc :
            // Si le paramètre 'category' est présent et n'est pas vide, je le convertit en (int), sinon null
            $idCategory = isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null;

            // Même logique pour la platforme
            $idPlatform = isset($_GET['platform']) && $_GET['platform'] !== '' ? (int) $_GET['platform'] : null;

            // Tri par défaut
            $sort = $_GET['sort'] ?? 'date_desc';
            // Page 1 minimum
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

            // J'envoie tous mes critères au Service
            $result = $this->adService->searchAds($searchQuery, $idCategory, $idPlatform, $sort, $page);

            // J'extrais les données du résultat
            $ads = $result['ads'];
            $totalPages = $result['totalPages'];
            $currentPage = $page; // Je stock la page courante pour que la vue sache sur quel bouton on est

            // Je demande au Service les listes complètes pour fabriquer les menus déroulants
            $formData = $this->adService->getFormData();
            $categories = $formData['categories'];
            $platforms = $formData['platforms'];

            // J'affiche la vue ( qui aura désormais accès à $ads, $categories, $platforms, $sort, $totalPages et $currentPage)
            include __DIR__ .'/../../template/home_page.php';

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur lors du chargement du catalogue : " . $err->getMessage()
            ];
            
            // Je prépare des données vide pour que la vue ne plante pas
            $ads = [];
            $categories = [];
            $platforms = [];
            $totalPages = 1;
            $currentPage = 1;
            $sort = 'date_desc';
            
            include __DIR__ .'/../../template/home_page.php';
        }
    }
}

?>