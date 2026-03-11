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
     * Affiche la page d'accueil avec toutes les annonces OU les résultats de recherche
     * URL : /Home ou /Home?q=mot_cle
     */
    public function index(?array $params) {
        try {
            // Je regarde s'il y'a une recherche dans l'URL (ex: ?q=cyber)
            // L'opérateur ?? '' met une chaîne vide si 'q' n'existe pas dans l'URL
            $searchQuery = $_GET['q'] ?? '';

            // Je demande les annonces au Service.
            // C'est lui qui fera le bon choix : tout le catalogue ou juste la recherche
            $ads = $this->adService->searchAds($searchQuery);

            include __DIR__ . '/../../template/home_page.php';
        } catch (Exception $err) {
            echo "Erreur lors du chargement de l'accueil : " . $err->getMessage();
        }
    }

}

?>