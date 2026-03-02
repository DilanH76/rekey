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

    public function findById(int $id): ?User
    {
        $sql="SELECT * FROM users WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user'=> $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null; // utilisateur non trouvé
        }

        // On recrée l'objet Entity User en utilisant le constructeur complet
        $user = new User (
            $row['last_name'],
            $row['first_name'],
            $row['pseudo'],
            $row['email'],
            $row['password'],
            new DateTime($row['created_at']), // Reconversion en DateTime
            $row['avatar'],
            (bool)$row['is_admin'],          
            $row['id_user']
        );

        return $user;
    }

    // Mettre à jour les informations du profil
    public function updateProfile(User $user): bool {
        $sql = "UPDATE users 
                SET last_name = :last_name, 
                    first_name = :first_name, 
                    pseudo = :pseudo, 
                    email = :email 
                WHERE id_user = :id_user";
                
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'last_name' => $user->getLastName(),
            'first_name' => $user->getFirstName(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'id_user' => $user->getIdUser()
        ]);
    }
    
}



// TODO autres fonctionnalitées à ajouter à termes : 
// findAll(): array (Pour le tableau de bord de Thomas l'Admin)
// delete(int $id): bool (Pour qu'un membre puisse supprimer son compte)
// Autre ??? ( CRUD )


?>