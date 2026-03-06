<?php
namespace App\Controller;

use App\Repository\AdRepository;
use \Exception;

/**
 * Contrôleur gérant la page d'accueil (Le Catalogue d'annonces)
 */
class HomeController {

    private AdRepository $adRepository;

    /**
     * Constructeur avec injection de dépendance
     * @param AdRepository $adRepository Pour récupérer les annonces dans la BDD
     */
    public function __construct(AdRepository $adRepository) 
    {
        $this->adRepository = $adRepository;
    }

    /**
     * Affiche la page d'accueil avec toutes les annonces
     * URL : /Home ou /
     */
    public function index(?array $params) {
        try {
            // Je récupère toutes les annonces avec leurs détails (Catégorie, Plateforme, User)
            $ads = $this->adRepository->findAllWithDetails();

            // J'affiche la vue en lui passant implicitement la variable $ads
            include __DIR__ . '/../../template/home_page.php';
        } catch (Exception $err) {
            echo "Erreur lors du chargement de l'accueil : " . $err->getMessage();
        }
    }
}

?>