<?php
namespace App\Controller;

use App\Service\AdService;
use \Exception;

/**
 * Contrôleur gérant les pages liées aux annonces (Création, affichage, etc.)
 */
class AdController {

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
     * Affiche le formulaire de création d'une nouvelle annonce
     * URL : /Ad/add
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function add(?array $params) {
        // L'utilisateur doit absolument être connecté pour vendre un jeu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        try {
            // Je demande au service de me fournir les catégories et platformes
            $formData = $this->adService->getFormData();

            // J'extrait les variables pour que la vue puisse les utiliser facilement
            $categories = $formData['categories'];
            $platforms = $formData['platforms'];

            // J'affiche la vue en lui passant implicitement $categories et $platforms
            include __DIR__ . '/../../template/add_ad.php';
        } catch (Exception $err) {
            echo "Erreur : " . $err->getMessage();
        }
    }

    // =========================================================
    // SECTION : TRAITEMENT DES FORMULAIRES
    // =========================================================

    /**
     * Traite la soumission du formulaire de création d'annonce
     * URL : /Ad/store
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function store(?array $params) {
        // Sécurité : l'utilisateur doit être connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // J'envoie tout le formulaire, les fichiers et l'ID du vendeur au service
                $this->adService->createAd($_POST, $_FILES, $_SESSION['user_id']);

                // SUCCES
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Félicitations ! Votre jeu a bien été mis en vente.'
                ];

                // Je redirige vers l'accueil
                header('Location: /Home');
                exit;
            } catch (Exception $err) {
                // ERREUR : Le service a levé une exception (prix négatif, image trop lourde...)
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => $err->getMessage()
                ];
                
                // Je le renvoie sur le formulaire pour qu'il corrige
                header('Location: /Ad/add');
                exit;
            }
        } else {
            // Si on accède à l'URL sans POST (en tapant l'URL manuellement)
            header('Location: /Ad/add');
            exit;
        }
    }
}
?>