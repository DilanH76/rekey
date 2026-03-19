<?php
namespace App\Controller;

use App\Service\ProfileService;
use \Exception;

/**
 * Contrôleur gérant l'espace personnel de l'utilisateur
 * (Affichage du profil, édition des infos/avatar, mot de passe et suppression de compte)
 */
class ProfileController extends BaseController {
    
    private ProfileService $profileService;
    
    /**
     * Constructeur avec injection de dépendance
     * @param ProfileService $profileService Le service métier gérant la logique du profil
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    // =========================================================
    // SECTION : AFFICHAGE DES VUES
    // =========================================================

    /**
     * Affiche la page principale du profil
     * URL : /Profile (ou /Profile/index)
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function index(?array $params) {
        // Verifier si l'utilisateur es connecté 
        $this->requireAuth();

        try {
            // Je demande au service les données de l'utilisateur
            // La variable $user contiendra un objet Entity\User complet
            $user = $this->profileService->getUserProfile($_SESSION['user_id']);
            // Je affiche la vue en lui passant l'objet $user
            include __DIR__ . '/../../template/profile.php';
        } catch (Exception $err) {
            // Si l'utilisateur n'est pas trouvé (ex: compte supprimé par l'admin entre temps)
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur d'accès au profil : " . $err->getMessage()
            ];
            // Je le redirige vers la déconnexion pour nettoyer sa session fantôme
            header('Location: /Auth/logout');
            exit;
        }
    }

    /**
     * Affiche le formulaire de modification du profil
     * URL : /Profile/edit
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function edit(?array $params) {
        // Vérifier si l'utilisateur es connecté
        $this->requireAuth();

        try {
            // Je demainde au service les données de l'utilisateur
            $user = $this->profileService->getUserProfile($_SESSION['user_id']);
            // J'affiche la vue en lui passant l'objet $user
            include __DIR__ . '/../../template/profile_edit.php';
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur d'accès au profil : " . $err->getMessage()
            ];
            header('Location: /Auth/logout');
            exit;
        }
    }

    // =========================================================
    // SECTION : TRAITEMENT DES MISES À JOUR (UPDATE)
    // =========================================================

    /**
     * Traite le formulaire de modification du profil (Infos classiques + Avatar)
     * URL : /Profile/update
     */
    public function update(?array $params) {
        $this->requireAuth();
        $this->requirePost('/Profile');

        try {
            // Je récupère l'utilisateur fraîchement mis à jour par le Service
            $updatedUser = $this->profileService->updateProfileData($_POST, $_FILES, $_SESSION['user_id']);
            
            // Je met à jour TOUTE la session pour que l'affichage (Header/Navbar) s'actualise instantanément 
            $_SESSION['user_pseudo'] = $updatedUser->getPseudo();
            $_SESSION['user_avatar'] = $updatedUser->getAvatarBase64();

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Vos informations ont été mises à jour.'
            ];
            header('Location: /Profile');
            exit;

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Profile/edit');
            exit;
        }
    }

    /**
     * Traite le formulaire de changement de mot de passe
     * URL : /Profile/updatePassword
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function updatePassword(?array $params) {
        // Verifier si l'utilisateur es connecté
        $this->requireAuth();
        $this->requirePost('/Profile');

        try {
            // J'envoie les données du formulaire au Service
            $this->profileService->changePassword($_SESSION['user_id'], $_POST);

            // Succès
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Votre mot de passe a été modifié avec succès.'
            ];
            header('Location: /Profile');
            exit;
        } catch (Exception $err) {
            // Erreur
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Profile/edit');
            exit;
        }
    }

    // =========================================================
    // SECTION : TRAITEMENT DE LA SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Traite la suppression définitive du compte utilisateur
     * URL : /Profile/deleteAccount
     * @param array|null $params Paramètres d'URL éventuels
     */
    public function deleteAccount(?array $params) {
        // Verifier si l'utilisateur est connecté
        $this->requireAuth();
        $this->requirePost('/Profile');

        try {
            // Je demande au service de supprimer le compte
            $this->profileService->deleteAccount($_SESSION['user_id']);

            // Je détruit la session actuelle ( déconnexion automatique )
            session_destroy();

            // Je recrée une session vierge juste pour le message d'adieu
            session_start();
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Votre compte a été definitivement supprimé. Au revoir !'
            ];

            header('Location: /Home');
            exit;
        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Profile/edit');
            exit;
        }
    }  
}
?>