<?php
namespace App\Controller;

use App\Service\AdService;
use \Exception;

/**
 * Contrôleur gérant les pages liées aux annonces (Création, affichage, etc.)
 */
class AdController extends BaseController {

    private AdService $adService;

    /**
     * Constructeur avec injection de dépendance
     * @param AdService $adService Le service métier gérant la logique des annonces
     */
    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    /**
     * Méthode par défaut si je tape juste /Ad dans l'URL
     * Je redirige vers l'accueil car il n'y a pas de page d'accueil spécifique aux annonces
     */
    public function index(?array $params) {
        header('Location: /Home');
        exit;
    }

    // =========================================================
    // SECTION : AFFICHAGE DES VUES
    // =========================================================


    /**
     * Affiche la page détaillée d'une annonce spécifique
     * URL : /Ad/show/123
     */
    public function show(?array $params) {
        $adId = isset($params[0]) ? (int)$params[0] : 0;

        if ($adId === 0) {
            header('Location: /Home');
            exit;
        }

        try {
            // Je récupère la vraie annonce via le Service
            $ad = $this->adService->getAdById($adId);
            
            //  J'affiche la vue (en lui passant $ad)
            include __DIR__ . '/../../template/ad_show.php';

        } catch (Exception $err) {
            // Si erreur (ex: annonce 999 n'existe pas), post-it rouge et retour accueil
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Home');
            exit;
        }
    }
    /**
     * Affiche le formulaire de création d'une nouvelle annonce
     * URL : /Ad/add
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function add(?array $params) {
        // L'utilisateur doit absolument être connecté pour vendre un jeu
        $this->requireAuth();

        try {
            // Je demande au service de me fournir les catégories et platformes
            $formData = $this->adService->getFormData();

            // J'extrait les variables pour que la vue puisse les utiliser facilement
            $categories = $formData['categories'];
            $platforms = $formData['platforms'];

            // J'affiche la vue en lui passant implicitement $categories et $platforms
            include __DIR__ . '/../../template/add_ad.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Home');
            exit;
        }
    }

    /**
     * Affiche la liste des annonces du vendeur connecté
     * URL : /Ad/mine
     */
    public function mine(?array $params) {
        $this->requireAuth();

        try {
            // Le service récupère uniqument  les annonces de ce vendeur
            $userAds = $this->adService->getUserAds($_SESSION['user_id']);

            include __DIR__ . '/../../template/my_ads.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur lors du chargement de vos annonces."
            ];
            header('Location: /Profile');
            exit;
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
        $this->requireAuth();
        
        // Si on accède à l'URL sans POST (en tapant l'URL manuellement)
        $this->requirePost('/Ad/add');

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
    }

    // =========================================================
    // SECTION : TRAITEMENT DES MISES À JOUR (UPDATE)
    // =========================================================

    /**
     * Affiche le formulaire de modification
     * URL : /Ad/edit/123
     */
    public function edit(?array $params) {
        $this->requireAuth();

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            // Je récupère l'annonce
            $ad = $this->adService->getAdById($adId);

            if ($ad->getUser()->getIdUser() !== $_SESSION['user_id']) {
                throw new \Exception("Accès refusé : vous n'êtes pas le vendeur de ce jeu.");
            }

            // Je prépare les menus déroulants
            $formData = $this->adService->getFormData();
            $categories = $formData['categories'];
            $platforms = $formData['platforms'];

            include __DIR__ . '/../../template/ad_edit.php';
        } catch (\Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Profile');
            exit;
        }
    }

    /**
     * Traite le formulaire de modification
     * URL : /Ad/update/123
     */
    public function update(?array $params) {
        $this->requireAuth('/Home');
        $this->requirePost('/Home');

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            $this->adService->updateAdData($adId, $_POST, $_SESSION['user_id']);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Annonce modifiée avec succès !'
            ];
            header('Location: /Ad/show/' . $adId);
            exit; 

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Ad/edit/'. $adId);
            exit;
        }
    }

    // =========================================================
    // SECTION : TRAITEMENT DE LA SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Traite la demande de suppression d'une annonce
     * URL : /Ad/delete/123
     */
    public function delete(?array $params) {
        $this->requireAuth();
        $this->requirePost();

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            // J'envoie l'ID de l'annoonce et l'ID de l'utilisateur connecté au Service
            $this->adService->deleteAd($adId, $_SESSION['user_id']);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Votre annonce a été supprimée avec succès.'
            ];
            header('Location: /Profile');
            exit;
        } catch (Exception $err) {

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Ad/show/'. $adId);
            exit;
        }
    }
}
?>