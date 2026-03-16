<?php
namespace App\Controller;

use App\Service\AdService;
use App\Service\CategoryService;
use App\Service\PlatformService;
use App\Service\ProfileService;
use \Exception;

/**
 * Contrôleur en charge de l'espace d'administration (Back-Office).
 * Restreint aux utilisateurs disposant du rôle administrateur.
 */
class AdminController extends BaseController {

    private AdService $adService;
    private ProfileService $profileService;
    private CategoryService $categoryService;
    private PlatformService $platformService;

    /**
     * Constructeur avec injection des dépendances requises.
     * @param AdService $adService Service gérant la logique métier des annonces
     * @param ProfileService $profileService Service gérant la logique métier des utilisateurs
     * @param CategoryService $categoryService Service gérant la logique métier des catégories (genres de jeux)
     * @param PlatformService $platformService Service gérant la logique métier des plateformes (consoles/PC)
     */
    public function __construct(AdService $adService, ProfileService $profileService, CategoryService $categoryService, PlatformService $platformService)
    {
        $this->adService = $adService;
        $this->profileService = $profileService;
        $this->categoryService = $categoryService;
        $this->platformService = $platformService;
    }

    // =========================================================
    // SECTION : AFFICHAGE DES VUES
    // =========================================================

    /**
     * Méthode par défaut si on tape juste /Admin dans l'URL.
     * Redirige automatiquement vers le tableau de bord.
     * @param array|null $params
     * @return void
     */
    public function index(?array $params): void 
    {
        header('Location: /Admin/dashboard');
        exit;
    }

    /**
     * Affiche le tableau de bord principal de l'administration.
     * Route : /Admin/dashboard
     * * @param array|null $params Paramètres d'URL éventuels
     * @return void
     */
    public function dashboard(?array $params): void
    {
        // Validation des droits d'accès
        $this->requireAdmin();

        try {
            // TODO : Intégration future des statistiques globales (utilisateurs, annonces)
            
            include __DIR__ . '/../../template/admin_dashboard.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => 'Erreur lors du chargement du tableau de bord : ' . $err->getMessage()
            ];
            header('Location: /Home');
            exit;
        }
    }

    /**
     * Affiche la liste de tous les utilisateurs inscrits.
     * Route : /Admin/users
     * @param array|null $params
     * @return void
     */
    public function users(?array $params): void
    {
        // Validation des droits d'accès
        $this->requireAdmin();

        try {
            // Récupération des données via le Service
            $users = $this->profileService->getAllUsers();

            // Affichage de la vue
            include __DIR__ . '/../../template/admin_users.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Erreur lors du chargement des utilisateurs : ' . $err->getMessage()
            ];
            header('Location: /Admin/dashboard');
            exit;
        }
    }

    /**
     * Supprime un utilisateur de la base de données (Bannissement).
     * Route : /Admin/deleteUser/{id}
     * @param array|null $params Paramètres d'URL contenant l'ID de l'utilisateur
     * @return void
     */
    public function deleteUser(?array $params): void
    {
        // Validation des droits d'accès
        $this->requireAdmin();

        //Vérification de la méthode HTTP (sécurité contre les suppressions par simple lien GET)
        $this->requirePost('/Admin/users');

        $userIdToDelete = isset($params[0]) ? (int)$params[0] : 0;

        // interdire l'auto-suppression depuis le pannel
        if ($userIdToDelete === $_SESSION['user_id']) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => 'Action refusée : Vous ne pouvez pas supprimer votre propre compte Administrateur ici.'
            ];
            header('Location: /Admin/users');
            exit;
        }

        try {

            // Appel au Service pour la suppression
            $this->profileService->deleteAccount($userIdToDelete);

            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'L\'utilisateur a été définitivement banni et toutes ses annonces ont été supprimées.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => 'Erreur lors de la suppression : ' . $err->getMessage()
            ];
        }
        header('Location: /Admin/users');
        exit;
    }

    // =========================================================
    // SECTION : MODÉRATION DES ANNONCES
    // =========================================================

    /**
     * Affiche la liste de toutes les annonces pour la modération.
     * Route : /Admin/ads
     * @param array|null $params
     * @return void
     */
    public function ads(?array $params): void
    {
        $this->requireAdmin();

        try {
            // Récupération de toutes les annonces sans exception
            $ads = $this->adService->getAllAdsForAdmin();

            include __DIR__ .'/../../template/admin_ads.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Erreur lors du chargement des annonces : ' . $err->getMessage()
            ];
            header('Location: /Admin/dashboard');
            exit;
        }
    }

    /**
     * Supprime une annonce depuis le panel d'administration.
     * Route : /Admin/deleteAd/{id}
     * @param array|null $params Paramètres d'URL contenant l'ID de l'annonce
     * @return void
     */
    public function deleteAd(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/ads');

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            $this->adService->deleteAdAsAdmin($adId);
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'L\'annonce a été supprimée avec succès par la modération.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => 'Erreur lors de la suppression : ' . $err->getMessage()
            ];
        }
        header('Location: /Admin/ads');
        exit;
    }

    // =========================================================
    // SECTION : GESTION DES CATÉGORIES ET PLATEFORMES
    // =========================================================

    /**
     * Affiche la page de gestion des Catégories et des Plateformes.
     * Route : /Admin/categories
     * @param array|null $params
     * @return void
     */
    public function categories(?array $params): void
    {
        $this->requireAdmin();

        try {
            // Je récupère les deux listes pour les afficher
            $categories = $this->categoryService->getAllCategories();
            $platforms = $this->platformService->getAllPlatforms();

            include __DIR__.'/../../template/admin_categories.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Erreur lors du chargement des données : ' . $err->getMessage()
            ];
            header('Location: /Admin/dashboard');
            exit;
        }
    }

    /**
     * Traite l'ajout d'une nouvelle catégorie
     * Route : /Admin/addCategory
     * @param array|null $params
     */
    public function addCategory(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('Admin/categories');

        try {
            $this->categoryService->createCategory($_POST);
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Catégorie ajoutée avec succès.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
        }
        header('Location: /Admin/categories');
        exit;
    }
    /**
     * Traite la suppression d'une catégorie
     * Route : /Admin/deleteCategory/{id}
     */
    public function deleteCategory(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/categories');

        $id = isset($params[0]) ? (int)$params[0] : 0;

        try {
            $this->categoryService->deleteCategory($id);
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'Catégorie supprimée.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => $err->getMessage()
            ];
        }
        header('Location: /Admin/categories');
        exit;
    }

    /**
     * Traite l'ajout d'une nouvelle plateforme
     * Route : /Admin/addPlatform
     */
    public function addPlatform(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/categories');

        try {
            $this->platformService->createPlatform($_POST);
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'Plateforme ajoutée avec succès.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => $err->getMessage()
            ];
        }
        header('Location: /Admin/categories');
        exit;
    }

    /**
     * Traite la suppression d'une plateforme
     * Route : /Admin/deletePlatform/{id}
     */
    public function deletePlatform(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/categories');

        $id = isset($params[0]) ? (int)$params[0] : 0;

        try {
            $this->platformService->deletePlatform($id);
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'Plateforme supprimée.'
            ];
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => $err->getMessage()
            ];
        }
        header('Location: /Admin/categories');
        exit;
    }

}
?>