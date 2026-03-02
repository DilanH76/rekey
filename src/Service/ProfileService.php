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

    // Récupérer les données fraîches de l'utilisateur connecté
    public function getUserProfile(int $userId): User {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }
        
        return $user;
    }
        
    // On préparera la méthode updateProfileData() ici plus tard...
}



?>