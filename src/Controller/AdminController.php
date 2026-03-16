<?php
namespace App\Controller;

use App\Service\AdService;
use App\Service\ProfileService;
use \Exception;

/**
 * Contrôleur en charge de l'espace d'administration (Back-Office).
 * Restreint aux utilisateurs disposant du rôle administrateur.
 */
class AdminController {

    private AdService $adService;
    private ProfileService $profileService;

/**
     * Constructeur avec injection des dépendances requises.
     * * @param AdService $adService Service gérant la logique métier des annonces
     * @param ProfileService $profileService Service gérant la logique métier des utilisateurs
     */
    public function __construct(AdService $adService, ProfileService $profileService)
    {
        $this->adService = $adService;
        $this->profileService = $profileService;
    }

    // =========================================================
    // SECTION : SÉCURITÉ 
    // =========================================================

    /**
     * Vérifie les autorisations d'accès à l'espace d'administration.
     * Redirige vers la page d'accueil avec un message d'erreur si l'utilisateur 
     * n'est pas authentifié ou ne possède pas les privilèges requis.
     * * @return void
     */
    private function checkAdminAccess(): void
    {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Accès refusé. Privilèges insuffisants.'
            ];
            header('Location: /Home');
            exit;
        }
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
        $this->checkAdminAccess();

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
        $this->checkAdminAccess();

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
        $this->checkAdminAccess();

        //Vérification de la méthode HTTP (sécurité contre les suppressions par simple lien GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Admin/users');
            exit;
        }

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
}
?>