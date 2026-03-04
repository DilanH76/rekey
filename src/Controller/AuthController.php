<?php
namespace App\Controller;

use App\Service\AuthService;
use \Exception;

/**
 * Contrôleur gérant l'authentification des utilisateurs
 * (Inscription, Connexion, Déconnexion)
 */
class AuthController {
    
    private AuthService $authService;

    /**
     * Constructeur avec injection de dépendance
     * * @param AuthService $authService Le service métier gérant la logique d'authentification
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Méthode par défaut du contrôleur
     * Redirige automatiquement vers la page de connexion
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function index(?array $params) {
        header('Location: /Auth/login');
        exit;
    }

    // =========================================================
    // SECTION : INSCRIPTION
    // =========================================================

    /**
     * Affiche le formulaire d'inscription
     * URL : /Auth/register 
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function register(?array $params) {
        include __DIR__.'/../../template/register.php';
    }

    /**
     * Traite les données du formulaire d'inscription (POST)
     * URL : /Auth/processRegister
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function processRegister(?array $params) {
        // Sécurité : on s'assure que la requête vient bien d'un formulaire
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Auth/register');
            exit;
        }
        
        try {
            // Le service fait tout le travail (vérification, hachage, sauvegarde en BDD)
            $this->authService->registerUser($_POST);

            // SUCCÈS : On met le Post-it vert et on redirige vers le Login
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Inscription effectuée avec succès ! Vous pouvez vous connecter.'
            ];
            header('Location: /Auth/login');
            exit;

        } catch (Exception $err) {
            // ERREUR (ex: email déjà pris, mot de passe trop faible)
            // On met le Post-it rouge et on redirige vers l'inscription
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Auth/register');
            exit;
        }
    }

    // =========================================================
    // SECTION : CONNEXION
    // =========================================================

    /**
     * Affiche le formulaire de connexion
     * URL : /Auth/login
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function login(?array $params) {
        include __DIR__ . '/../../template/login.php';
    }

    /**
     * Traite les données du formulaire de connexion (POST)
     * URL : /Auth/processLogin
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function processLogin(?array $params) {
        // Sécurité : on s'assure que la requête vient bien d'un formulaire
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Auth/login');
            exit;
        }

        try {
            // Le service vérifie les identifiants et nous renvoie l'entité User si c'est bon
            $user = $this->authService->loginUser($_POST);
            
            // SUCCÈS : On connecte l'utilisateur en stockant ses infos clés en session
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
            // ERREUR : Identifiants incorrects (Post-it rouge)
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Auth/login');
            exit;
        }
    }

    // =========================================================
    // SECTION : DÉCONNEXION
    // =========================================================

    /**
     * Déconnecte l'utilisateur et détruit sa session
     * URL : /Auth/logout
     * * @param array|null $params Paramètres d'URL éventuels
     */
    public function logout(?array $params) {
        // On détruit la session actuelle (efface user_id, user_pseudo, etc.)
        session_destroy();
        
        // ASTUCE : On redémarre une session vierge juste pour pouvoir coller le Post-it flash !
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