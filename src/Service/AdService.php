<?php
namespace App\Service;

use App\Repository\AdRepository;
use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use App\Entity\Ad;
use \Exception;
use \DateTime;

/**
 * Service gérant la logique métier des annonces (jeux à vendre)
 */
class AdService {

    private AdRepository $adRepository;
    private CategoryRepository $categoryRepository;
    private PlatformRepository $platformRepository;

    /**
     * Constructeur avec injection des 3 repositories nécessaires
     */
    public function __construct(AdRepository $adRepository, CategoryRepository $categoryRepository, PlatformRepository $platformRepository)
    {
        $this->adRepository = $adRepository;
        $this->categoryRepository = $categoryRepository;
        $this->platformRepository = $platformRepository;
    }

    // =========================================================
    // SECTION : PRÉPARATION DES DONNÉES (POUR LES VUES)
    // =========================================================

    /**
     * Récupère les catégories et les plateformes pour populer les menus déroulants du formulaire
     * @return array Un tableau associatif contenant les deux listes
     */
    public function getFormData(): array
    {
        return [
            'categories' => $this->categoryRepository->findAll(),
            'platforms' => $this->platformRepository->findAll()
        ];
    }

    // =========================================================
    // SECTION : TRAITEMENT (CREATE)
    // =========================================================

    /**
     * Traite le formulaire de création d'une annonce
     * @param array $postData Les données saisies ($_POST)
     * @param array $files L'image potentiellement uploadée ($_FILES)
     * @param int $userId L'ID de l'utilisateur qui vend le jeu
     * @return void
     * @throws Exception Si les données sont invalides (prix négatif, image trop lourde, etc.)
     */
    public function createAd(array $postData, array $files, int $userId): void
    {
        // Verification de base
        if (empty(trim($postData['title'])) || empty(trim($postData['game_key']))) {
            throw new Exception("Le titre et la clé du jeu sont obligatoires.");
        }

        $price = (float) $postData['price'];
        if ($price < 0) {
            throw new Exception("Le prix ne peut pas être négatif.");
        }

        // Traitement de l'image de couverture (si elle existe)
        $coverBlob = null;
        if (isset($files['cover_image']) && $files['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $files['cover_image'];

            // Vérification de la taille (2Mo max)
            $maxSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception("L'image est trop lourde (Maximum 2 Mo).");
            }
        
            // Vérification du format
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($file['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Format non supporté. Veuillez utiliser du JPG, PNG ou WEBP.");
            }

            // Convertion en BLOB
            $coverBlob = file_get_contents($file['tmp_name']);
        }

        // Création de l'entité Ad
        $ad = new Ad(
            trim($postData['title']),
            trim($postData['description']),
            $price,
            $coverBlob,
            trim($postData['game_key']),
            'disponible', // Par défaut, une nouvelle annonce est disponible
            new DateTime(),
            (int) $postData['id_platform'],
            (int) $postData['id_category'],
            $userId
        );

        // Envoie au Repository pour la sauvegarde en BDD
        $success = $this->adRepository->create($ad);
        
        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la création de l'annonce."); 
        }
    }

    // =========================================================
    // SECTION : LECTURE (READ)
    // =========================================================

    /**
     * Récupère une annonce par son ID
     * @param int $id L'ID de l'annonce à chercher
     * @return Ad L'objet complet
     * @throws Exception Si l'annonce n'existe pas
     */
    public function getAdById(int $id): Ad
    {
        $ad = $this->adRepository->findByIdWithDetails($id);

        if (!$ad) {
            throw new Exception("Cette annonce n'existe pas ou a été supprimée.");
        }

        return $ad;
    }

    /**
     * Récupère toutes les annonces publiées par un vendeur
     * @param int $userId L'ID de l'utilisateur
     * @return array Un tableau d'objets Ad
     */
    public function getUserAds(int $userId): array
    {
        return $this->adRepository->findByUserIdWithDetails($userId);
    }
}
?>