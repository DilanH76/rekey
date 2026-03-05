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


}
?>