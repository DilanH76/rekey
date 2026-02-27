<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use \Exception;
use \DateTime;

class AuthService {
    private UserRepository $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository=$userRepository;
    }

    public function registerUser(array $postData) 
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
        // On hache le mot de passe
        $hashedPassword = password_hash($postData['password'], PASSWORD_DEFAULT);
        // on créer l'entité
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