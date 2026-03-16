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
    // SECTION : CRÉATION (CREATE)
    // =========================================================

    /**
     * Ajoute une nouvelle plateforme en base de données
     * @param Platform $platform L'objet Platform contenant le nom et l'icône
     * @return bool True si succès, False sinon
     */
    public function create(Platform $platform): bool
    {
        $sql = "INSERT INTO platforms (label, icon_svg) VALUES (:label, :icon_svg)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'label' => $platform->getLabel(),
            'icon_svg' => $platform->getIconSvg()
        ]);
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
        // Je trie par ordre alphabétique
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

    // =========================================================
    // SECTION : MISE À JOUR (UPDATE)
    // =========================================================

    /**
     * Met à jour les informations d'une plateforme existante
     * @param Platform $platform L'objet Platform contenant les nouvelles infos et l'ID
     * @return bool True si succès, False sinon
     */
    public function update(Platform $platform): bool
    {
        $sql = "UPDATE platforms SET label = :label, icon_svg = :icon_svg WHERE id_platform = :id_platform";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            'label' => $platform->getLabel(),
            'icon_svg' => $platform->getIconSvg(),
            'id_platform' => $platform->getIdPlatform()
        ]);
    }

    // =========================================================
    // SECTION : SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Supprime une plateforme de la base de données
     * @param int $id L'identifiant de la plateforme
     * @return bool True si succès, False sinon
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM platforms WHERE id_platform = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}
?>