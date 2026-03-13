<?php
namespace App\Repository;

use App\Entity\Category;
use \PDO;

/**
 * Repository gérant les requêtes SQL pour la table 'categories'
 */
class CategoryRepository {

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
     * Récupère toutes les catégories de la base de données
     * Utile pour populer les menus déroulants de création d'annonce
     * @return array Un tableau contenant des objets Category
     */
    public function findAll(): array
    {
        // Je trie par ordre alphabétique pour que le menu déroulant soit propre
        $sql = "SELECT * FROM categories ORDER BY label ASC"; 
        
        // Comme il n'y a pas de variable dans la requête, 
        // je peut utiliser query() au lieu de prepare()
        $stmt = $this->pdo->query($sql); 

        $categories = [];
        
        // je boucle sur chaque ligne retournée par la BDD
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // j'instancie un objet Category et on l'ajoute au tableau
            $categories[] = new Category(
                $row['label'],
                $row['id_category']
            );
        }

        return $categories; // je renvoie le tableau
    }
}
?>