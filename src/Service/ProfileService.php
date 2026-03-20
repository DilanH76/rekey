<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use \Exception;

/**
 * Service gérant la logique métier du profil utilisateur
 */
class ProfileService {
    
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    // =========================================================
    // SECTION : LECTURE DES DONNÉES
    // =========================================================

    public function getAllUsers(): array 
    {
        return $this->userRepository->findAll();
    }

    public function getUserProfile(int $userId): User {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }
        return $user;
    }

    public function countTotalUsers(): int {
        return $this->userRepository->countTotalUsers();
    }
        
    // =========================================================
    // SECTION : MISE À JOUR DES DONNÉES
    // =========================================================

    /**
     * Met à jour les informations générales et l'avatar du profil
     * Retourne l'objet User mis à jour pour rafraîchir la session
     */
    public function updateProfileData(array $data, array $files, int $userId): User {
        $user = $this->getUserProfile($userId);
        
        // Nettoyage Anti-XSS strict
        $newPseudo = htmlspecialchars(trim($data['pseudo'] ?? ''));
        $newEmail  = trim($data['email'] ?? ''); // Pas de htmlspecialchars car il y'a la regex
        $newFirstName = htmlspecialchars(trim($data['first_name'] ?? ''));
        $newLastName = htmlspecialchars(trim($data['last_name'] ?? ''));

        // Vérification des champs vides
        if (empty($newPseudo) || empty($newEmail) || empty($newFirstName) || empty($newLastName)) {
            throw new Exception("Tous les champs texte sont obligatoires.");
        }

        // Verification du pseudo
        if ($newPseudo !== $user->getPseudo()) {
            $existingUser = $this->userRepository->findByEmailOrPseudo($newPseudo);
            if ($existingUser !== null && $existingUser->getIdUser() !== $userId) {
                throw new Exception("Ce pseudo est déjà utilisé.");
            }
        }

        $regexEmail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($regexEmail, $newEmail)) {
            throw new Exception("L'adresse email n'est pas au bon format (ex: joueur@gmail.com).");
        }

        // Nettoyage après validation
        $newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);

        // Verification de l'email
        if ($newEmail !== $user->getEmail()) {
            $existingUser = $this->userRepository->findByEmailOrPseudo($newEmail);
            if ($existingUser !== null && $existingUser->getIdUser() !== $userId) {
                throw new Exception("Cet email est déjà associé à un autre compte.");
            }
        }

        // Traitement de l'avatar
        if (isset($files['avatar']) && $files['avatar']['error'] === UPLOAD_ERR_OK ) {
            $file = $files['avatar'];

            $maxSize = 2 * 1024 * 1024; // 2Mo
            if ($file['size'] > $maxSize) {
                throw new Exception("L'image est trop lourde (Maximum 2 Mo).");
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($file['tmp_name']); 

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Format non supporté. Veuillez utiliser du JPG, PNG ou WEBP.");
            }

            $blobData = file_get_contents($file['tmp_name']);
            $user->setAvatar($blobData); 
        }   

        // Mise à jour de l'entité avec les données nettoyées
        $user->setPseudo($newPseudo);
        $user->setEmail($newEmail);
        $user->setFirstName($newFirstName);
        $user->setLastName($newLastName);
        
        $success = $this->userRepository->updateProfile($user);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la sauvegarde de votre profil.");
        }

        // On retourne l'utilisateur pour le Controller
        return $user;
    }

    // =========================================================
    // SECTION : SÉCURITÉ & SUPPRESSION
    // =========================================================

    public function changePassword(int $userId, array $data): void {
        $user = $this->getUserProfile($userId);

        $oldPassword = $data['old_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        
        if (!password_verify($oldPassword, $user->getPassword())) {
            throw new Exception("L'ancien mot de passe est incorrect.");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("Les nouveaux mots de passe ne correspondent pas.");
        }

        $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($regexPassword, $newPassword)) {
            throw new Exception("Le nouveau mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&).");
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $success = $this->userRepository->updatePassword($userId, $hashedPassword);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la sauvegarde du mot de passe.");
        }
    }

    public function deleteAccount(int $userId): void {
        $user = $this->getUserProfile($userId);
        $success = $this->userRepository->delete($userId);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la suppression de votre compte");
        }
    }
}
?>