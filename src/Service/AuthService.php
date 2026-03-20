<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use \Exception;
use \DateTime;

/**
 * Service gérant la logique métier de l'authentification
 * (Vérification des données d'inscription, hachage, et connexion)
 */
class AuthService {
    
    private UserRepository $userRepository;

    /**
     * Constructeur avec injection de dépendance
     * @param UserRepository $userRepository Le repository pour dialoguer avec la table users
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // =========================================================
    // SECTION : LOGIQUE D'INSCRIPTION
    // =========================================================

    /**
     * Traite l'inscription d'un nouvel utilisateur
     * Vérifie les doublons, la robustesse du mot de passe et crée l'entité.
     *  @param array $postData Les données brutes issues du formulaire ($_POST)
     * @return User L'utilisateur nouvellement créé
     * @throws Exception Si une vérification échoue (email pris, mot de passe faible, etc.)
     */
    public function registerUser(array $postData): User 
    {
        // Sécurité Back-end
        // Je vérifie que tous les champs obligatoires existent et ne sont pas juste des espaces (trim)
        if (
            empty(trim($postData['last_name'] ?? '')) ||
            empty(trim($postData['first_name'] ?? '')) ||
            empty(trim($postData['pseudo'] ?? '')) ||
            empty(trim($postData['email'] ?? '')) ||
            empty(trim($postData['password'] ?? '')) ||
            empty(trim($postData['password_confirm'] ?? ''))
        ) {
            throw new Exception("Tous les champs sont obligatoires. Merci de remplir le formulaire correctement.");
        }

        $regexEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($regexEmail, $postData['email'])) {
            throw new Exception("L'adresse email n'est pas au bon format (ex: joueur@gmail.com).");
        }

        $cleanEmail = filter_var(trim($postData['email']), FILTER_SANITIZE_EMAIL);

        // Je vérifie si l'email existe déjà via le Repository
        $existingEmail = $this->userRepository->findByEmailOrPseudo($cleanEmail);
        if ($existingEmail) {
            throw new Exception("Cet email est déjà utilisé.");
        }
        
        // Je vérifie si le pseudo est déjà pris
        $existingpseudo = $this->userRepository->findByEmailOrPseudo($postData['pseudo']);
        if ($existingpseudo) {
            throw new Exception("Ce pseudo est déjà utilisé.");
        }


        // Minimum 8 caractères, au moins une majuscule, une minuscule, un chiffre et un caractère spécial
        $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/' ;

        if (!preg_match($regexPassword, $postData['password'])) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).");
        }

        // Je vérifie les mots de passes
        if ($postData['password'] !== $postData['password_confirm']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }

        // Je hache le mot de passe
        $hashedPassword = password_hash($postData['password'], PASSWORD_DEFAULT);

        // Nettoyage des données pour bloquer les failles XSS (Sanitization)
        $cleanLastName = htmlspecialchars(trim($postData['last_name']));
        $cleanFirstName = htmlspecialchars(trim($postData['first_name']));
        $cleanPseudo = htmlspecialchars(trim($postData['pseudo']));
        $cleanEmail = filter_var(trim($postData['email']), FILTER_SANITIZE_EMAIL);
        
        // Je crée l'entité avec les données propres
        $user = new User(
            $cleanLastName,
            $cleanFirstName,
            $cleanPseudo,
            $cleanEmail,
            $hashedPassword,
            new DateTime(),
            null // Avatar par défaut géré dans l'Entity User.
        );

        // Je dis au Repository de sauvegarder
        $this->userRepository->register($user);

        return $user;
    }
    
    // =========================================================
    // SECTION : LOGIQUE DE CONNEXION
    // =========================================================

    /**
     * Traite la tentative de connexion d'un utilisateur
     * * @param array $postData Les données brutes issues du formulaire ($_POST)
     * @return User L'objet Entity\User correspondant à l'utilisateur connecté
     * @throws Exception Si l'identifiant n'existe pas ou si le mot de passe est faux
     */
    public function loginUser(array $postData): User
    {
        $loginInput = $postData['login'] ?? '';
        $passwordInput = $postData['password'] ?? '';
        // Je cherche l'utilisateur par son email ou pseudo ( champ 'login' du formulaire )
        $user = $this->userRepository->findByEmailOrPseudo($loginInput);
        
        // si l'utilisateur n'existe pas
        if (!$user) {
            throw new Exception("Identifiant ou mot de passe incorrect.");
        }
        
        // Je vérifie le mot de passe hashé
        if (!password_verify($passwordInput, $user->getPassword())) {
            throw new Exception("Identifiant ou mot de passe incorrect.");
        }
        
        // Si j'arrive ici tout est valide. Je retourne l'objet User.
        return $user;
    }
}
?>