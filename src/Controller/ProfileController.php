<?php
namespace App\Controller;

use App\Service\ProfileService;
use \Exception;

class ProfileController {
    private ProfileService $profileService;
    
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    // Affichage du profil
    // URL : /Profil (ou /Profil/index)
    public function index(?array $params) {
        // Verifier si l'utilisateur es connecté 
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        try {
            // On demande au service les données de l'utilisateur
            // La variable $user contiendra un objet Entity\User complet
            $user = $this->profileService->getUserProfile($_SESSION['user_id']);
            // On affiche la vue en lui passant l'objet $user
            include __DIR__ . '/../../template/profile.php';
        } catch (Exception $err) {
            // Si l'utilisateur n'est pas trouvé
            echo "Erreur : " . $err->getMessage();

        }
    }

    // Affichage du formulaire de modification du profil
    // URL : /Profile/edit
    public function edit(?array $params) {
        // Vérifier si l'utilisateur es connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        try {
            // On demainde au service les données de l'utilisateur
            $user = $this->profileService->getUserProfile($_SESSION['user_id']);
            // On affiche la vue en lui passant l'objet $user
            include __DIR__ . '/../../template/profile_edit.php';
        } catch (Exception $err) {
            echo "Erreur : " . $err->getMessage();
        }
    }

    // Traitement du formulaire de modification du profil
    // URL : /Profile/update
    public function update(?array $params) {
        // Vérifier si l'utilisateur es connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // On envoie tout le formulaire au sercie
                $this->profileService->updateProfileData($_POST, $_SESSION['user_id']);
                // On met à jour le pseudo dans la session
                $_SESSION['user_pseudo'] = trim($_POST['pseudo']);
                // SUCCÈS
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Vos informations ont été mises à jour.'
                ];
                header('Location: /Profile');
                exit;

            } catch (Exception $err) {
                // ERREUR 
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => $err->getMessage()
                ];
                header('Location: /Profile/edit');
                exit;
            }
        } else {
            // Si on accède à l'URL sans POST, on renvoie sur le profil
            header('Location; /Profile');
            exit;
        }
    }
    
}



?>