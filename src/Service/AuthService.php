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
     * * @param array $postData Les données brutes issues du formulaire ($_POST)
     * @return void
     * @throws Exception Si une vérification échoue (email pris, mot de passe faible, etc.)
     */
    public function registerUser(array $postData): void 
    {
        // On vérifie si l'email existe déjà via le Repository
        $existingEmail = $this->userRepository->findByEmailOrPseudo($postData['email']);
        if ($existingEmail) {
            throw new Exception("Cet email est déjà utilisé.");
        }
        
        // On vérifie si le pseudo est déjà pris
        $existingpseudo = $this->userRepository->findByEmailOrPseudo($postData['pseudo']);
        if ($existingpseudo) {
            throw new Exception("Ce pseudo est déjà utilisé.");
        }

        // On vérifie les mots de passes
        if ($postData['password'] !== $postData['password_confirm']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        }
        
        // Minimum 8 caractères, au moins une majuscule, une minuscule, un chiffre et un caractère spécial
        $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/' ;

        if (!preg_match($regexPassword, $postData['password'])) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).");
        }

        // On hache le mot de passe
        $hashedPassword = password_hash($postData['password'], PASSWORD_DEFAULT);
        
        // on crée l'entité
        $user = new User(
            $postData['last_name'],
            $postData['first_name'],
            $postData['pseudo'],
            $postData['email'],
            $hashedPassword,
            new DateTime(),
            null // Avatar par défaut géré dans l'Entity User.
        );

        // On dit au Repository de sauvegarder
        $this->userRepository->register($user);
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
        // On cherche l'utilisateur par son email ou pseudo ( champ 'login' du formulaire )
        $user = $this->userRepository->findByEmailOrPseudo($postData['login']);
        
        // si l'utilisateur n'existe pas
        if (!$user) {
            throw new Exception("Identifiant ou mot de passe incorrect.");
        }
        
        // On vérifie le mot de passe hashé
        if (!password_verify($postData['password'], $user->getPassword())) {
            throw new Exception("Identifiant ou mot de passe incorrect.");
        }
        
        // Si on arrive ici tout est valide. On retourne l'objet User.
        return $user;
    }
}
?>