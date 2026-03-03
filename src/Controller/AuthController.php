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

    public function index(?array $params) {
        header('Location: /Auth/login');
        exit;
    }

    // --- INSCRIPTION ---

    // Affiche le formulaire d'inscription
    // URL: /Auth/register 
    public function register(?array $params) {
        include __DIR__.'/../../template/register.php';
    }

    // Traite le formulaire d'inscription
    // URL: /Auth/processRegister
    public function processRegister(?array $params) {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Auth/register');
            exit;
        }
        
        try {
            // Le service fait tout le travail
            $this->authService->registerUser($_POST);

            // SUCCÈS : On met le Post-it vert et on redirige vers le Login
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Inscription effectuée avec succès ! Vous pouvez vous connecter.'
            ];
            header('Location: /Auth/login');
            exit;

        } catch (Exception $err) {
            // ERREUR : On met le Post-it rouge et on redirige vers l'inscription
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Auth/register');
            exit;
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Auth/login');
            exit;
        }

        try {
            $user = $this->authService->loginUser($_POST);
            
            // SUCCÈS : On connecte l'utilisateur
            $_SESSION['user_id'] = $user->getIdUser();
            $_SESSION['user_pseudo'] = $user->getPseudo();
            $_SESSION['is_admin'] = $user->getIsAdmin();
            
            // On met un petit Post-it vert de bienvenue 
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Ravi de vous revoir, ' . htmlspecialchars($user->getPseudo()) . ' !'
            ];
            header('Location: /Home');
            exit;

        } catch (Exception $err) {
            // ERREUR : Post-it rouge
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Auth/login');
            exit;
        }
    }

    // Déconnexion
    // URL: /Auth/logout
    public function logout(?array $params) {
        // On détruit la session actuelle
        session_destroy();
        
        // ASTUCE : On redémarre une session vierge juste pour pouvoir coller le Post-it !
        session_start();
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Vous êtes bien déconnecté. À bientôt !'
        ];
        
        // On redirige vers l'accueil
        header('Location: /Home');
        exit;
    }
}
?>