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
    // SECTION : CRÉATION (CREATE)
    // =========================================================

    /**
     * Ajoute une nouvelle catégorie en base de données
     * @param Category $category L'objet Category contenant le nom
     * @return bool True si succès, False sinon
     */
    public function create(Category $category): bool
    {
        $sql = "INSERT INTO categories (label) VALUES (:label)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['label' => $category->getLabel()]);
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

    // =========================================================
    // SECTION : MISE À JOUR (UPDATE)
    // =========================================================

    /**
     * Met à jour le nom d'une catégorie existante
     * @param Category $category L'objet Category contenant le nouveau nom et l'ID à modifier
     * @return bool True si succès, False sinon
     */
    public function update(Category $category): bool
    {
        $sql = "UPDATE categories SET label = :label WHERE id_category = :id_category";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            'label' => $category->getLabel(),
            'id_category' => $category->getIdCategory()
        ]);
    }

    // =========================================================
    // SECTION : SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Supprime une catégorie de la base de données
     * @param int $id L'identifiant de la catégorie
     * @return bool True si succès, False sinon
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE id_category = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>