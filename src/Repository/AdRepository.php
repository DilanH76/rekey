<?php
namespace App\Repository;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Platform;

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
            $category = new Category(
                $row['category_label'], 
                (int) $row['id_category']
            );
            $ad->setCategory($category);

            // La plateforme
            $platform = new Platform(
                $row['platform_icon'],
                $row['platform_label'], 
                (int) $row['id_platform']
            );
            $ad->setPlatform($platform);

            // Le vendeur
            $user = new User(
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
        $category = new Category(
            $row['category_label'],
            (int) $row['id_category']
        );
        $ad->setCategory($category);

        $platform = new Platform(
            $row['platform_icon'],
            $row['platform_label'],
            (int) $row['id_platform']
        );
        $ad->setPlatform($platform);

        $user = new User(
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
            $category = new Category(
                $row['category_label'], 
                (int) $row['id_category']);
            $ad->setCategory($category);

            $platform = new Platform(
                $row['platform_icon'], 
                $row['platform_label'], 
                (int) $row['id_platform']);
            $ad->setPlatform($platform);

            $user = new User(
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
     * Recherche dynamique avec filtres, tri et pagination
     * @param string $search Le texte recherché (vide si aucun)
     * @param int|null $idCategory L'ID de la catégorie (null si aucune)
     * @param int|null $idPlatform L'ID de la plateforme (null si aucune)
     * @param string $sort Le type de tri (date_desc, price_asc, price_desc)
     * @param int $limit Le nombre maximum d'annonces à renvoyer
     * @param int $offset Le point de départ pour la pagination
     * @return array Un tableau d'objets Ad
     */
    public function searchAndFilter(string $search, ?int $idCategory, ?int $idPlatform, string $sort = 'date_desc', int $limit = 12, int $offset = 0): array
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
            // Si je cherche, j'affiche TOUT ce qui correspond (Titre du jeu OU Pseudo), peu importe le statut
            $sql .= " AND (a.title LIKE :search OR u.pseudo LIKE :search)";
            $params['search'] = "%" . $search . '%';
        } else {
            // Si je ne cherche rien (affichage par défaut de l'accueil), je ne montre que les jeux dispos
            $sql .= " AND a.status = 'disponible'";
        }

        if ($idCategory !== null) {
            $sql .= " AND a.id_category = :id_category";
            $params['id_category'] = $idCategory;
        }

        if ($idPlatform !== null) {
            $sql .= " AND a.id_platform = :id_platform";
            $params['id_platform'] = $idPlatform;
        }

        // Gestion du tri (ORDER BY dynamique)
        if ($sort === 'price_asc') {
            $sql .= " ORDER BY a.price ASC";
        } elseif ($sort === 'price_desc') {
            $sql .= " ORDER BY a.price DESC";
        } else {
            $sql .= " ORDER BY a.created_at DESC";
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        // je bind les paramètres de recherche (texte, catégorie, plateforme)
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        // Je bind la pagination en forçant le type INT (sinon PDO plante)
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        // j'exécute à vide (car j'ai tout bindé manuellement juste au-dessus)
        $stmt->execute();

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
            $category = new Category(
                $row['category_label'], 
                (int) $row['id_category']
            );
            $ad->setCategory($category);

            // La plateforme
            $platform = new Platform(
                $row['platform_icon'],
                $row['platform_label'], 
                (int) $row['id_platform']
            );
            $ad->setPlatform($platform);

            // Le vendeur
            $user = new User(
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
     * Compte le nombre total d'annonces correspondant aux filtres
     * Indispensable pour calculer le nombre de pages pour la pagination
     * @param string $search Le texte recherché (vide si aucun)
     * @param int|null $idCategory L'ID de la catégorie (null si aucune)
     * @param int|null $idPlatform L'ID de la plateforme (null si aucune)
     * @return int Le nombre total d'annonces trouvées
     */
    public function countAdsWithFilters(string $search, ?int $idCategory, ?int $idPlatform): int
    {
        $sql = "SELECT COUNT(*) FROM ads a
                INNER JOIN users u ON a.id_user = u.id_user 
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            // Je compte TOUT ce qui correspond à la recherche (Titre ou Pseudo)
            $sql .= " AND (a.title LIKE :search OR u.pseudo LIKE :search)";
            $params['search'] = "%" . $search . "%";
        } else {
            // Sinon je ne compte que les annonces dispos
            $sql .= " AND a.status = 'disponible'";
        }

        if ($idCategory !== null) {
            $sql .= " AND a.id_category = :id_category";
            $params['id_category'] = $idCategory;
        }

        if ($idPlatform !== null) {
            $sql .= " AND a.id_platform = :id_platform";
            $params['id_platform'] = $idPlatform;
        }

        // Ici, pas besoin de ORDER BY ni de LIMIT/OFFSET, je veux juste le compte global
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // fetchColumn() récupère directement la première colonne de la première ligne (mon COUNT)
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compte le nombre total d'annonces selon leur statut ('disponible' ou 'vendu')
     * @param string $status Le statut à cibler
     * @return int
     */
    public function countAdsByStatus(string $status): int {
        $sql = "SELECT COUNT(*) FROM ads WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
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

    /**
     * Met à jour le statut d'une annonce pour la marquer comme vendue
     * @param int $adId L'identifiant de l'annonce
     * @return bool
     */
    public function markAsSold(int $adId): bool
    {
        $sql = "UPDATE ads SET status = 'vendu' WHERE id_ads = :id_ads";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_ads' => $adId]);
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