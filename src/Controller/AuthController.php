<?php
namespace App\Controller;

use App\Service\AuthService;
use \Exception;

class AuthController {
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // --- INSCRIPTION ---

    // Affiche le formulaire d'inscription
    // URL: /Auth ou /Auth/index
    public function index(?array $params) {
        include __DIR__.'/../../template/register.php';
    }

    // Traite le formulaire d'inscription
    // URL: /Auth/register
    public function register(?array $params) {
        // On tente directement l'action
        try {
            // Le service fait tout le travail ( vérif email, mdr, hash, etc .. )
            $this->authService->registerUser($_POST);

            include __DIR__ . '/../../template/register.php';
            $this->modale("Inscription effectuée avec succès !Vous pouvez vous connecter.", true, "/Auth/login");
        } catch (Exception $err) {
            // Si il y'a une erreur jetée par le Service (email déjà pris etc)
            $errorMessage = $err->getMessage();

            // On réaffiche le formulaire
            include __DIR__. '/../../template/register.php';
            // Et la modal en mode erreur
            $this->modale($errorMessage, false, "/Auth");

        }
    }

    // --- CONNEXION ---

    // Affiche le formulaire de connexion
    // URL : /Auth/login
    public function login(?array $params) {
        include __DIR__ . '/../../template/login.php';
    }

    // Traite le formulaire de connexion
    // URL : /Auth/processLogin
    public function processLogin(?array $params) {
        try {
            // Le service vérifie les identifiants et nous renvoie l'utilisateur
            $user = $this->authService->loginUser($_POST);
            // SUCCES : On connecte l'utilisateur en enregistrant son ID dans la session
            $_SESSION['user_id'] = $user->getIdUser();
            $_SESSION['user_pseudo'] = $user->getPseudo();
            $_SESSION['is_admin'] = $user->getIsAdmin();
            // on redirige vers la page d'accueil ou le dashboard si admin
            header('Location: /Home');
            exit;
        } catch (Exception $err) {
            // Mauvais mot de passe ou email
            $errorMessage = $err ->getMessage();
            // on réaffiche le formulaire de connexion
            include __DIR__ . '/../../template/login.php';
            // On affiche la modale d'erreur
            $this->modale($errorMessage, false, "/Auth/login");
        }
    }

    // Déconnexion
    // URL: /Auth/logout
    public function logout(?array $params) {
        // On détruit la session
        session_destroy();
        // On redirige vers l'accueil
        header('Location: /Home');
        exit;

    }


    
    // MODALE
    public function modale($message, $success, $url) {
        include __DIR__ . '/../../template/message_modal.php';
    }
}
?>