<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use \Exception;

class ProfileService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    // Récupérer les données de l'utilisateur connecté
    public function getUserProfile(int $userId): User {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }
        
        return $user;
    }
        
    // Mettre à jour les données du profil
    public function updateProfileData(array $data, int $userId): void {
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
}



?>