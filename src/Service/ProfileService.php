<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use \Exception;

/**
 * Service gérant la logique métier du profil utilisateur
 * (Récupération, mise à jour des infos/avatar, modification du mot de passe et suppression)
 */
class ProfileService {
    
    private UserRepository $userRepository;

    /**
     * Constructeur avec injection de dépendance
     * @param UserRepository $userRepository Le repository pour dialoguer avec la table users
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    // =========================================================
    // SECTION : LECTURE DES DONNÉES
    // =========================================================

    /**
     * Récupère les données d'un utilisateur par son ID
     * @param int $userId L'identifiant de l'utilisateur
     * @return User L'objet Entity\User correspondant
     * @throws Exception Si l'utilisateur n'existe pas en base de données
     */
    public function getUserProfile(int $userId): User {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }
        
        return $user;
    }
        
    // =========================================================
    // SECTION : MISE À JOUR DES DONNÉES
    // =========================================================

    /**
     * Met à jour les informations générales et l'avatar du profil
     * @param array $data Les données du formulaire ($_POST)
     * @param array $files Les fichiers uploadés ($_FILES)
     * @param int $userId L'identifiant de l'utilisateur
     * @return void
     * @throws Exception Si un pseudo/email est déjà pris, ou si l'avatar est invalide
     */
    public function updateProfileData(array $data, array $files, int $userId): void {
        // On récupère l'utilisateur actuel grâce à la méthode qu'on a déjà
        $user = $this->getUserProfile($userId);

        $newPseudo = trim($data['pseudo']);
        $newEmail  = trim($data['email']);

        // Verification du pseudo
        // Si le pseudo a changé, on vérifie qu'il n'est pas déjà pris
        if ($newPseudo !== $user->getPseudo()) {
            $existingUser = $this->userRepository->findByEmailOrPseudo($newPseudo);
            if ($existingUser !== null && $existingUser->getIdUser() !== $userId) {
                throw new Exception("Ce pseudo est déjà utilisé.");
            }
        }

        // Verification de l'email
        // Si l'email a changé, on vérifie qu'il n'est pas déjà pris
        if ($newEmail !== $user->getEmail()) {
            $existingUser = $this->userRepository->findByEmailOrPseudo($newEmail);
            if ($existingUser !== null && $existingUser->getIdUser() !== $userId) {
                throw new Exception("Cet email est déjà associé à un autre compte.");
            }
        }

        // Traitement de l'avatar
        if (isset($files['avatar']) && $files['avatar']['error'] === UPLOAD_ERR_OK ) {
            $file = $files['avatar'];

            // Vérification de la taille
            $maxSize = 2 * 1024 * 1024; // 2Mo en octets
            if ($file['size'] > $maxSize) {
                throw new Exception("L'image est trop lourde (Maximum 2 Mo).");
            }

            // Verification du format
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($file['tmp_name']); // Lit le vrai type du fichier

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Format non supporté. Veuillez utiliser du JPG, PNG ou WEBP.");
            }

            // Conversion en BLOB
            $blobData = file_get_contents($file['tmp_name']);
            $user->setAvatar($blobData); // On met à jour l'entité
        }   

        // On met à jour l'entité avec les nouvelles données du formulaire
        $user->setPseudo($newPseudo);
        $user->setEmail($newEmail);
        $user->setFirstName(trim($data['first_name']));
        $user->setLastName(trim($data['last_name']));
        
        // On envoie l'objet mis à jour au Repository pour la sauvegarde en BDD
        $success = $this->userRepository->updateProfile($user);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la sauvegarde de votre profil.");
        }
    }

    // =========================================================
    // SECTION : SÉCURITÉ & SUPPRESSION
    // =========================================================

    /**
     * Gère la modification sécurisée du mot de passe
     * @param int $userId L'identifiant de l'utilisateur
     * @param array $data Les mots de passe saisis ($_POST)
     * @return void
     * @throws Exception Si les vérifications (ancien mdp, correspondance, regex) échouent
     */
    public function changePassword(int $userId, array $data): void {
        // On récupère l'utilisateur actuel
        $user = $this->getUserProfile($userId);
        
        // On vérifie l'ancien mot de passe tapé correspond bien à celui en BDD
        if (!password_verify($data['old_password'], $user->getPassword())) {
            throw new Exception("L'ancien mot de passe est incorrect.");
        }

        // On vérifie que les deux nouveaux mots de passe correspondent
        if ($data['new_password'] !== $data['confirm_password']) {
            throw new Exception("Les nouveaux mots de passe ne correspondent pas.");
        }

        // On vérifie la robustesse avec la Regex
        $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($regexPassword, $data['new_password'])) {
            throw new Exception("Le nouveau mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).");
        }

        // On hache le nouveau mot de passe
        $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

        // On l'envoie au Repository pour la sauvegarde
        $success = $this->userRepository->updatePassword($userId, $hashedPassword);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la sauvegarde du mot de passe.");
        }
    }

    /**
     * Supprime définitivement le compte d'un utilisateur
     * @param int $userId L'identifiant de l'utilisateur
     * @return void
     * @throws Exception Si la suppression échoue en base de données
     */
    public function deleteAccount(int $userId): void {
        // On vérifie que l'utilisateur existe bien avant de le supprimer
        $user = $this->getUserProfile($userId);

        $success = $this->userRepository->delete($userId);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la suppression de votre compte");
        }
    }
}
?>