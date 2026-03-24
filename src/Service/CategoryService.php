<?php 
namespace App\Service;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use \Exception;
use \PDOException;

/**
 * Service gérant la logique métier des Catégories
 */
class CategoryService {

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Récupère toutes les catégories existantes
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Traite la création d'une nouvelle catégorie
     * @throws Exception Si le nom est vide ou invalide
     */
    public function createCategory(string $label): void
    {
        
        if (empty($label)) {
            throw new Exception("Le nom de la catégorie ne peut pas être vide.");
        }

        // j'instancie la catégorie
        $category = new Category($label);

        if (!$this->categoryRepository->create($category)) {
            throw new Exception("Erreur technique lors de la création de la catégorie.");
        }
    }

    /**
     * Traite la mise à jour d'une catégorie
     * @param int $id L'ID de la catégorie à modifier
     * @param array $data Données du formulaire ($_POST)
     * @throws Exception Si le nom est vide
     */
    public function updateCategory(int $id, array $data): void
    {
        $label = trim($data['label'] ?? '');

        if (empty($label)) {
            throw new Exception("Le nom de la catégorie ne peut pas être vide.");
        }

        // J'instancie la catégorie avec son ID
        $category = new Category($label, $id);

        if (!$this->categoryRepository->update($category)) {
            throw new Exception("Erreur technique lors de la modification de la catégorie.");
        }
    }

    /**
     * Traite la suppression d'une catégorie
     * @param int $id L'ID de la catégorie
     * @throws Exception Si la catégorie est encore utilisée par des annonces
     */
    public function deleteCategory(int $id): void
    {
        try {
            $success = $this->categoryRepository->delete($id);
            if (!$success) {
                throw new Exception("La catégorie n'a pas pu être supprimée.");
            }
        } catch (PDOException $e) {
            // Le code 23000 correspond à une violation de contrainte d'intégrité (Clé étrangère)
            if ($e->getCode() == '23000') {
                throw new Exception("Impossible de supprimer cette catégorie car des annonces l'utilisent encore. Veuillez d'abord modifier ou supprimer ces annonces.");
            }
            throw new Exception("Une erreur base de données est survenue : " . $e->getMessage());
        }
    }
}
?>