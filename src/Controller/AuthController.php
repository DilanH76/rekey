<?php
namespace App\Controller;

use App\Service\AuthService;
use \Exception;

/**
 * Contrôleur gérant l'authentification des utilisateurs
 * (Inscription, Connexion, Déconnexion)
 */
class AuthController extends BaseController {
    
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
        // Sécurité : je m'assure que la requête vient bien d'un formulaire
        $this->requirePost('/Auth/register');
        
        try {
            // Le service fait tout le travail (vérification, hachage, sauvegarde en BDD)
            $this->authService->registerUser($_POST);

            // SUCCÈS : Je met le Post-it vert et je redirige vers le Login
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Inscription effectuée avec succès ! Vous pouvez vous connecter.'
            ];
            header('Location: /Auth/login');
            exit;

        } catch (Exception $err) {
            // ERREUR (ex: email déjà pris, mot de passe trop faible)
            // Je met le Post-it rouge et je redirige vers l'inscription
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
        // Sécurité : je m'assure que la requête vient bien d'un formulaire
        $this->requirePost('/Auth/login');

        try {
            // Le service vérifie les identifiants et me renvoie l'entité User si c'est bon
            $user = $this->authService->loginUser($_POST);
            
            // SUCCÈS : Je connecte l'utilisateur en stockant ses infos clés en session
            $_SESSION['user_id'] = $user->getIdUser();
            $_SESSION['user_pseudo'] = $user->getPseudo();
            $_SESSION['is_admin'] = $user->getIsAdmin();
            
            // Je met un petit Post-it vert de bienvenue 
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
        // Je détruit la session actuelle (efface user_id, user_pseudo, etc.)
        session_destroy();
        
        // ASTUCE : Je redémarre une session vierge juste pour pouvoir coller le Post-it flash
        session_start();
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Vous êtes bien déconnecté. À bientôt !'
        ];
        
        // Je redirige vers l'accueil
        header('Location: /Home');
        exit;
    }
}
?>