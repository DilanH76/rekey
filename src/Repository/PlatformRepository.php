<?php
namespace App\Repository;

use App\Entity\Platform;
use \PDO;

/**
 * Repository gérant les requêtes SQL pour la table 'platforms'
 */

class PlatformRepository {

    private PDO $pdo;

    /**
     * Constructeur avec injection de la connexion PDO
     */

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================================================
    // SECTION : LECTURE (READ)
    // =========================================================

    /**
     * Récupère toutes les plateformes de la base de données
     * Utile pour populer les menus déroulants de création d'annonce
     * @return array Un tableau contenant des objets Platform
     */
    public function findAll(): array
    {
        // On trie par ordre alphabétique
        $sql = "SELECT * FROM platforms ORDER BY label ASC";

        $stmt = $this->pdo->query($sql);

        $platforms = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $platforms[] = new Platform(
                $row['icon_svg'],
                $row['label'],
                $row['id_platform']
            );
        }

        return $platforms;
    }

}
?>