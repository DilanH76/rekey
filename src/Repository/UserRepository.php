<?php
namespace App\Repository;

use App\Entity\User;
use DateTime;
use \PDO;


class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo=$pdo;
    }

    // --- INSCRIPTION ---
    // Ajout d'un nouvel utilisateur en BDD

    public function register(User $user): bool 
    {
        $sql = "INSERT INTO users (last_name, first_name, pseudo, email, password, is_admin, created_at, avatar) VALUES (:last_name, :first_name, :pseudo, :email, :password, :is_admin, :created_at, :avatar)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':pseudo', $user->getPseudo());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        // PDO::PARAM_BOOL pour s'assurer que c'est bien traité comme un booléen/entier
        $stmt->bindValue(':is_admin', $user->getIsAdmin(), PDO::PARAM_BOOL);
        // MySQL attend une date au format texte 'YYYY-MM-DD HH:MM:SS'
        $stmt->bindValue(':created_at', $user->getCreatedAt()->format('Y-m-d H:i:s'));
        $avatar = $user->getAvatar();
        if ($avatar === null) {
            // Si l'utilisateur n'a pas mis d'avatar
            $stmt->bindValue(':avatar', null, PDO::PARAM_NULL);
        } else {
            // Si oui : PARAM_LOB indique à PDO qu'on envoie des données binaires lourdes
            $stmt->bindValue(':avatar', $avatar, PDO::PARAM_LOB);
        }   

        return $stmt->execute();
    }


    // --- INSCRIPTION & CONNEXION ---
    // Trouver un utilisateur par Email

    public function findByEmailOrPseudo(string $identifier): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :identifier OR pseudo = :identifier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);

        $row = $stmt->fetch();

        if (!$row) return  null; // Si aucun utilisateur n'a cet email, on renvoi null

        $user = new User (
            $row['last_name'],
            $row['first_name'],
            $row['pseudo'],
            $row['email'],
            $row['password'],
            new DateTime($row['created_at']), // Reconvertion en DateTime
            $row['avatar'],
            (bool)$row['is_admin'],          // TODO Verifier si le cast ( (bool) ) est obligatoire sinon -> On force le booléen.
            $row['id_user']
        );

        return $user;
    }
}

// TODO autres fonctionnalitées à ajouter à termes : 
// findById(int $id): ?User (Pour afficher le profil d'un membre)
// findAll(): array (Pour le tableau de bord de Thomas l'Admin)
// update(User $user): bool (Pour que Sarah change son avatar ou son pseudo)
// delete(int $id): bool (Pour qu'un membre puisse supprimer son compte)
// Autre ??? ( CRUD )


?>