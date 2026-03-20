<?php
namespace App\Service;

use App\Repository\PlatformRepository;
use App\Entity\Platform;
use \Exception;
use \PDOException;

/**
 * Service gérant la logique métier des Plateformes
 */
class PlatformService {

    private PlatformRepository $platformRepository;

    public function __construct(PlatformRepository $platformRepository)
    {
        $this->platformRepository = $platformRepository;
    }

    /**
     * Récupère toutes les plateformes existantes
     */
    public function getAllPlatforms(): array
    {
        return $this->platformRepository->findAll();
    }

    /**
     * Traite la création d'une nouvelle plateforme
     * @throws Exception Si les champs sont vides
     */
    public function createPlatform(string $label, string $iconSvg): void
    {
        if (empty($label) || empty($iconSvg)) {
            throw new Exception("Le nom et le chemin de l'icône SVG sont obligatoires.");
        }

        $platform = new Platform($iconSvg, $label);

        if (!$this->platformRepository->create($platform)) {
            throw new Exception("Erreur technique lors de la création de la plateforme.");
        }
    }

    /**
     * Traite la mise à jour d'une plateforme
     * @param int $id L'ID de la plateforme à modifier
     * @param array $data Données du formulaire ($_POST)
     * @throws Exception Si les champs sont vides
     */
    public function updatePlatform(int $id, array $data): void
    {
        $label = trim($data['label'] ?? '');
        $iconSvg = trim($data['icon_svg'] ?? '');

        if (empty($label) || empty($iconSvg)) {
            throw new Exception("Le nom et le chemin de l'icône SVG sont obligatoires.");
        }

        $platform = new Platform($iconSvg, $label, $id);

        if (!$this->platformRepository->update($platform)) {
            throw new Exception("Erreur technique lors de la modification de la plateforme.");
        }
    }

    /**
     * Traite la suppression d'une plateforme
     * @param int $id L'ID de la plateforme
     * @throws Exception Si la plateforme est encore utilisée par des annonces
     */
    public function deletePlatform(int $id): void
    {
        try {
            $success = $this->platformRepository->delete($id);
            if (!$success) {
                throw new Exception("La plateforme n'a pas pu être supprimée.");
            }
        } catch (PDOException $e) {
            // Le code 23000 correspond à une violation de contrainte d'intégrité (Clé étrangère)
            if ($e->getCode() == '23000') {
                throw new Exception("Impossible de supprimer cette plateforme car des annonces l'utilisent encore.");
            }
            throw new Exception("Une erreur base de données est survenue : " . $e->getMessage());
        }
    }
}
?>