<?php
namespace App\Repository;

use App\Entity\User;
use DateTime;
use \PDO;

/**
 * Repository gérant toutes les requêtes SQL liées à la table 'users'
 * C'est la seule classe autorisée à communiquer avec la base de données pour les utilisateurs.
 */
class UserRepository {
    
    private PDO $pdo;

    /**
     * Constructeur avec injection de la connexion PDO
     * @param PDO $pdo L'instance de connexion à la base de données
     */
    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    // =========================================================
    // SECTION : CRÉATION (CREATE)
    // =========================================================

    /**
     * Ajoute un nouvel utilisateur en BDD
     * @param User $user L'objet contenant toutes les informations à sauvegarder
     * @return bool True si l'insertion a réussi, False sinon
     */
    public function register(User $user): bool 
    {
        $sql = "INSERT INTO users (last_name, first_name, pseudo, email, password, is_admin, created_at, avatar) 
                VALUES (:last_name, :first_name, :pseudo, :email, :password, :is_admin, :created_at, :avatar)";

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
            // Si oui : PARAM_LOB indique à PDO qu'on envoie des données binaires lourdes (BLOB)
            $stmt->bindValue(':avatar', $avatar, PDO::PARAM_LOB);
        }   

        return $stmt->execute();
    }

    // =========================================================
    // SECTION : LECTURE (READ)
    // =========================================================

    /**
     * Trouve un utilisateur par son Email ou son Pseudo
     * Utile pour la connexion ou pour vérifier si un email/pseudo est déjà pris.
     * @param string $identifier L'email ou le pseudo recherché
     * @return User|null Retourne l'objet User si trouvé, sinon null
     */
    public function findByEmailOrPseudo(string $identifier): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :identifier OR pseudo = :identifier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null; // Si aucun utilisateur n'a cet email/pseudo, je renvoie null

        // Je recrée l'objet Entity User en utilisant le constructeur complet
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

    /**
     * Trouve un utilisateur via son ID unique
     * @param int $id L'identifiant de l'utilisateur
     * @return User|null Retourne l'objet User si trouvé, sinon null
     */
    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null; // utilisateur non trouvé
        }

        $user = new User (
            $row['last_name'],
            $row['first_name'],
            $row['pseudo'],
            $row['email'],
            $row['password'],
            new DateTime($row['created_at']), 
            $row['avatar'],
            (bool)$row['is_admin'],          
            $row['id_user']
        );

        return $user;
    }

    /**
     * Récupère la liste de tous les utilisateurs inscrits
     * @return array Un tableau contenant des objets User
     */
    public function findAll(): array {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);

        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User(
                $row['last_name'],
                $row['first_name'],
                $row['pseudo'],
                $row['email'],
                $row['password'],
                new DateTime($row['created_at']),
                $row['avatar'],
                (bool)$row['is_admin'],
                $row['id_user']
            );
        }
        return $users;
    }

    // =========================================================
    // SECTION : MISE À JOUR (UPDATE)
    // =========================================================

    /**
     * Met à jour les informations du profil (Infos classiques + Avatar)
     * @param User $user L'objet User contenant les nouvelles données
     * @return bool True en cas de succès, False sinon
     */
    public function updateProfile(User $user): bool {
        $sql = "UPDATE users 
                SET last_name = :last_name, 
                    first_name = :first_name, 
                    pseudo = :pseudo, 
                    email = :email,
                    avatar = :avatar 
                WHERE id_user = :id_user";
                
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':pseudo', $user->getPseudo());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':id_user', $user->getIdUser());

        $avatar = $user->getAvatar();
        if ($avatar === null) {
            $stmt->bindValue(':avatar', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':avatar', $avatar, PDO::PARAM_LOB);
        }

        return $stmt->execute();
    }

    /**
     * Met à jour uniquement le mot de passe (Requête dédiée pour plus de sécurité)
     * @param int $userId L'identifiant de l'utilisateur
     * @param string $hashedPassword Le nouveau mot de passe déjà haché
     * @return bool True en cas de succès, False sinon
     */
    public function updatePassword(int $userId, string $hashedPassword): bool {
        $sql = "UPDATE users SET password = :password WHERE id_user = :id_user";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':id_user', $userId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    // =========================================================
    // SECTION : SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Supprime définitivement un utilisateur de la BDD
     * @param int $id L'identifiant de l'utilisateur à supprimer
     * @return bool True en cas de succès, False sinon
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_user' => $id]);
    }
    
}
?>