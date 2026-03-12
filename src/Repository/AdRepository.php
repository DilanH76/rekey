<?php
namespace App\Repository;

use App\Entity\Ad;
use \PDO;

/**
 * Repository gérant toutes les requêtes SQL liées à la table 'ads' (Annonces)
 */

class AdRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================================================
    // SECTION : CRÉATION (CREATE)
    // =========================================================

    /**
     * Ajoute une nouvelle annonce en BDD
     * @param Ad $ad L'objet contenant toutes les informations du jeu à vendre
     * @return bool True si l'insertion a réussi, False sinon
     */
    public function create(Ad $ad): bool
    {
        $sql="INSERT INTO ads (title, description, price, cover_image, game_key, status, created_at, id_platform, id_category, id_user) 
              VALUES (:title, :description, :price, :cover_image, :game_key, :status, :created_at, :id_platform, :id_category, :id_user)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':title', $ad->getTitle());
        $stmt->bindValue(':description', $ad->getDescription());
        $stmt->bindValue(':price', $ad->getPrice());

        // Gestion de l'image de couverture (BLOB)
        $cover = $ad->getCoverImage();
        if ($cover === null) {
            $stmt->bindValue(':cover_image', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':cover_image', $cover, PDO::PARAM_LOB);
        }

        $stmt->bindValue(':game_key', $ad->getGameKey());
        $stmt->bindValue(':status', $ad->getStatus());
        $stmt->bindValue(':created_at', $ad->getCreatedAt()->format('Y-m-d H:i:s'));

        // Les clés étrangères
        $stmt->bindValue(':id_platform', $ad->getIdPlatform(), PDO::PARAM_INT);
        $stmt->bindValue(':id_category', $ad->getIdCategory(), PDO::PARAM_INT);
        $stmt->bindValue(':id_user', $ad->getIdUser(), PDO::PARAM_INT);

        return $stmt->execute();

    }

    // =========================================================
    // SECTION : LECTURE (READ)
    // =========================================================

    /**
     * Récupère toutes les annonces avec les détails de la catégorie, plateforme et vendeur
     * @return array Un tableau contenant des objets Ad complètement hydratés
     */
    public function findAllWithDetails(): array
    {
        // Je sélectionne TOUT de la table ads (a.*)
        // plus les champs spécifiques des autres tables en les renommant (AS)
        $sql = "SELECT
                    a.*,
                    c.label AS category_label,
                    p.label AS platform_label, p.icon_svg AS platform_icon,
                    u.last_name, u.first_name, u.pseudo, u.email, u.password, u.is_admin, u.created_at AS user_created_at, u.avatar AS user_avatar
                FROM ads a
                INNER JOIN categories c ON a.id_category = c.id_category
                INNER JOIN platforms p ON a.id_platform = p.id_platform
                INNER JOIN users u ON a.id_user = u.id_user
                ORDER BY a.created_at DESC";

        $stmt = $this->pdo->query($sql);
        $ads =[];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // J'instancie l'annonce de base
            $ad = new Ad(
                $row['title'],
                $row['description'],
                (float) $row['price'],
                $row['cover_image'],
                $row['game_key'],
                $row['status'],
                new \DateTime($row['created_at']),
                (int) $row['id_platform'],
                (int) $row['id_category'],
                (int) $row['id_user'],
                (int) $row['id_ads']
            );

            // J'instancie les objets relationnels

            // La catégorie
            $category = new \App\Entity\Category(
                $row['category_label'], 
                (int) $row['id_category']
            );
            $ad->setCategory($category);

            // La plateforme
            $platform = new \App\Entity\Platform(
                $row['platform_icon'],
                $row['platform_label'], 
                (int) $row['id_platform']
            );
            $ad->setPlatform($platform);

            // Le vendeur
            $user = new \App\Entity\User(
                $row['last_name'],
                $row['first_name'],
                $row['pseudo'],
                $row['email'],
                $row['password'],
                new \DateTime($row['user_created_at']),
                $row['user_avatar'],
                (bool) $row['is_admin'],
                (int) $row['id_user']
            );
            $ad->setUser($user);

            // J'ajoute mon objet Ad (qui contient maintenant 3 autres objets) dans le tableau final
            $ads[] = $ad;
        }

        return $ads;
    }

    /**
     * Récupère UNE annonce spécifique avec tous ses détails (Catégorie, Plateforme, Vendeur)
     * @param int $id L'identifiant de l'annonce
     * @return Ad|null L'objet Ad complet, ou null si introuvable
     */
    public function findByIdWithDetails(int $id): ?Ad
    {
        $sql = "SELECT
                    a.*,
                    c.label AS category_label,
                    p.label AS platform_label, p.icon_svg AS platform_icon,
                    u.last_name, u.first_name, u.pseudo, u.email, u.password, u.is_admin, u.created_at AS user_created_at, u.avatar AS user_avatar
                FROM ads a
                INNER JOIN categories c ON a.id_category = c.id_category
                INNER JOIN platforms p ON a.id_platform = p.id_platform
                INNER JOIN users u ON a.id_user = u.id_user
                WHERE a.id_ads = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null; // Si l'annonce n'existe pas en BDD
        }

        // Instanciation de l'annonce
        $ad = new Ad(
            $row['title'],
            $row['description'],
            (float) $row['price'],
            $row['cover_image'],
            $row['game_key'],
            $row['status'],
            new \DateTime($row['created_at']),
            (int) $row['id_platform'],
            (int) $row['id_category'],
            (int) $row['id_user'],
            (int) $row['id_ads']
        );

        // instanciation et association des objets liés
        $category = new \App\Entity\Category(
            $row['category_label'],
            (int) $row['id_category']
        );
        $ad->setCategory($category);

        $platform = new \App\Entity\Platform(
            $row['platform_icon'],
            $row['platform_label'],
            (int) $row['id_platform']
        );
        $ad->setPlatform($platform);

        $user = new \App\Entity\User(
            $row['last_name'],
            $row['first_name'],
            $row['pseudo'],
            $row['email'],
            $row['password'],
            new \DateTime($row['created_at']),
            $row['user_avatar'],
            (bool) $row['is_admin'],
            (int) $row['id_user']
        );
        $ad->setUser($user);

        return $ad;
    }

    /**
     * Récupère toutes les annonces publiées par un utilisateur spécifique
     * @param int $userId L'identifiant de l'utilisateur
     * @return array Un tableau d'objets Ad
     */
    public function findByUserIdWithDetails(int $userId): array
    {
        $sql = "SELECT
                    a.*,
                    c.label AS category_label,
                    p.label AS platform_label, p.icon_svg AS platform_icon,
                    u.last_name, u.first_name, u.pseudo, u.email, u.password, u.is_admin, u.created_at AS user_created_at, u.avatar AS user_avatar
                FROM ads a
                INNER JOIN categories c ON a.id_category = c.id_category
                INNER JOIN platforms p ON a.id_platform = p.id_platform
                INNER JOIN users u ON a.id_user = u.id_user
                WHERE a.id_user = :id_user
                ORDER BY a.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $userId]);

        $ads = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            // Instanciation de l'Annonce
            $ad = new Ad(
                $row['title'],
                $row['description'],
                (float) $row['price'],
                $row['cover_image'],
                $row['game_key'],
                $row['status'],
                new \DateTime($row['created_at']),
                (int) $row['id_platform'],
                (int) $row['id_category'],
                (int) $row['id_user'],
                (int) $row['id_ads']
            );

            // Instanciation et association des objets liés
            $category = new \App\Entity\Category(
                $row['category_label'], 
                (int) $row['id_category']);
            $ad->setCategory($category);

            $platform = new \App\Entity\Platform(
                $row['platform_icon'], 
                $row['platform_label'], 
                (int) $row['id_platform']);
            $ad->setPlatform($platform);

            $user = new \App\Entity\User(
                $row['last_name'],
                $row['first_name'],
                $row['pseudo'],
                $row['email'],
                $row['password'],
                new \DateTime($row['user_created_at']),
                $row['user_avatar'],
                (bool) $row['is_admin'],
                (int) $row['id_user']
            );
            $ad->setUser($user);

            $ads[] = $ad;
        }
        
        return $ads;
    }

    /**
     * Recherche dynamique avec filtres (Texte, Catégorie, Plateforme)
     * @param string $search Le texte recherché (vide si aucun)
     * @param int|null $idCategory L'ID de la catégorie (null si aucune)
     * @param int|null $idPlatform L'ID de la plateforme (null si aucune)
     * @return array Un tableau d'objets Ad
     */
    public function searchAndFilter(string $search, ?int $idCategory, ?int $idPlatform): array
    {
        $sql = "SELECT
                    a.*,
                    c.label AS category_label,
                    p.label AS platform_label, p.icon_svg AS platform_icon,
                    u.last_name, u.first_name, u.pseudo, u.email, u.password, u.is_admin, u.created_at AS user_created_at, u.avatar AS user_avatar
                FROM ads a
                INNER JOIN categories c ON a.id_category = c.id_category
                INNER JOIN platforms p ON a.id_platform = p.id_platform
                INNER JOIN users u ON a.id_user = u.id_user
                WHERE 1=1";
        $params = [];
        // Ajout dynamique des conditions si elles existent
        // J'entoure le mot recherché de "%" pour dire "Peu importe ce qu'il y'a avant ou après"
        // Exemple : Si $search ="cyber", ça trouvera "Cyberpunk2077"
        if (!empty($search)) {
            $sql .= " AND a.title LIKE :search";
            $params['search'] = "%" . $search . '%';
        }

        if ($idCategory !== null) {
            $sql .= " AND a.id_category = :id_category";
            $params['id_category'] = $idCategory;
        }

        if ($idPlatform !== null) {
            $sql .= " AND a.id_platform = :id_platform";
            $params['id_platform'] = $idPlatform;
        }

        $sql .= " ORDER BY a.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $ads = [];

        // Même "hydratation" que pour findAllWithDetails
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            
            $ad = new Ad(
                $row['title'],
                $row['description'],
                (float) $row['price'],
                $row['cover_image'],
                $row['game_key'],
                $row['status'],
                new \DateTime($row['created_at']),
                (int) $row['id_platform'],
                (int) $row['id_category'],
                (int) $row['id_user'],
                (int) $row['id_ads']
            );

            // J'instancie les objets relationnels

            // La catégorie
            $category = new \App\Entity\Category(
                $row['category_label'], 
                (int) $row['id_category']
            );
            $ad->setCategory($category);

            // La plateforme
            $platform = new \App\Entity\Platform(
                $row['platform_icon'],
                $row['platform_label'], 
                (int) $row['id_platform']
            );
            $ad->setPlatform($platform);

            // Le vendeur
            $user = new \App\Entity\User(
                $row['last_name'],
                $row['first_name'],
                $row['pseudo'],
                $row['email'],
                $row['password'],
                new \DateTime($row['user_created_at']),
                $row['user_avatar'],
                (bool) $row['is_admin'],
                (int) $row['id_user']
            );
            $ad->setUser($user);

            // J'ajoute mon objet Ad (qui contient maintenant 3 autres objets) dans le tableau final
            $ads[] = $ad;
        }

        return $ads;
    }

    // =========================================================
    // SECTION : MISE À JOUR (UPDATE)
    // =========================================================

    /**
     * Met à jour les informations textuelles d'une annonce
     */

    public function updateAdInfo(int $adId, string $title, string $description, float $price, int $idCategory, int $idPlatform): bool
    {
        $sql ="UPDATE ads SET
                    title = :title,
                    description = :description,
                    price = :price,
                    id_category = :id_category,
                    id_platform = :id_platform
               WHERE id_ads = :id_ads";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'id_category' => $idCategory,
            'id_platform' => $idPlatform,
            'id_ads' => $adId
        ]);
    }

    // =========================================================
    // SECTION : SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Supprime une annonce de la base de données
     * @param int $id L'ID de l'annonce à supprimer
     * @return bool True si la suppression a réussi, False sinon
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM ads WHERE id_ads = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}
?>