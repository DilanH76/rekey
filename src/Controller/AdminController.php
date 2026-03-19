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

    public function index(?array $params): void 
    {
        header('Location: /Admin/dashboard');
        exit;
    }

    public function dashboard(?array $params): void
    {
        $this->requireAdmin();

        try {
            $totalUsers = $this->profileService->countTotalUsers();
            $activeAds = $this->adService->countActiveAds();
            $totalSales = $this->adService->countSoldAds();
            
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

    public function users(?array $params): void
    {
        $this->requireAdmin();

        try {
            $users = $this->profileService->getAllUsers();
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

    public function deleteUser(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/users');

        $userIdToDelete = isset($params[0]) ? (int)$params[0] : 0;

        if ($userIdToDelete === $_SESSION['user_id']) {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'message' => 'Action refusée : Vous ne pouvez pas supprimer votre propre compte Administrateur ici.'
            ];
            header('Location: /Admin/users');
            exit;
        }

        try {
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

    public function ads(?array $params): void
    {
        $this->requireAdmin();

        try {
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

    public function categories(?array $params): void
    {
        $this->requireAdmin();

        try {
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
     */
    public function addCategory(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('Admin/categories');

        try {
            // MODIFICATION : On nettoie et on extrait la valeur avant de l'envoyer au service
            $label = htmlspecialchars(trim($_POST['label'] ?? ''));
            if (empty($label)) {
                throw new Exception("Le nom de la catégorie est obligatoire.");
            }
            
            // On envoie juste le texte nettoyé
            $this->categoryService->createCategory($label); 
            
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
     */
    public function addPlatform(?array $params): void
    {
        $this->requireAdmin();
        $this->requirePost('/Admin/categories');

        try {
            // Je nettoie les valeurs avant de les envoyer
            $label = htmlspecialchars(trim($_POST['label'] ?? ''));
            $iconSvg = htmlspecialchars(trim($_POST['icon_svg'] ?? ''));

            if (empty($label) || empty($iconSvg)) {
                throw new Exception("Tous les champs sont obligatoires pour la plateforme.");
            }
            
            // j'envoie les deux valeurs nettoyées au service
            $this->platformService->createPlatform($label, $iconSvg);

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