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
        // Nettoyage immédiat des données contre les failles XSS
        $cleanTitle = htmlspecialchars(trim($postData['title'] ?? ''));
        $cleanDesc = htmlspecialchars(trim($postData['description'] ?? ''));
        // Je trim juste la clé, j'évite htmlspecialchars au cas où elle contient des caractères spéciaux légitimes
        $gameKey = trim($postData['game_key'] ?? ''); 

        // Verification de base
        if (empty($cleanTitle) || empty($gameKey)) {
            throw new Exception("Le titre et la clé du jeu sont obligatoires.");
        }

        $price = (float) ($postData['price'] ?? 0);
        if ($price < 0) {
            throw new Exception("Le prix ne peut pas être négatif.");
        }


        // Si une exception est levée ici, le script s'arrête et l'annonce n'est pas créée.
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
            
        } elseif (isset($files['cover_image']) && $files['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // S'il y a eu une erreur pendant l'upload (ex: fichier corrompu, coupure réseau)
            throw new Exception("Une erreur est survenue lors du téléchargement de l'image.");
        }

        // Création de l'entité Ad
        $ad = new Ad(
            $cleanTitle,
            $cleanDesc,
            $price,
            $coverBlob,
            $gameKey,
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

    /**
     * Recherche des annonces avec filtres, tri et pagination
     * @param string $query Le texte tapé par l'utilisateur
     * @param int|null $idCategory L'ID de la catégorie
     * @param int|null $idPlatform L'ID de la plateforme
     * @return array Un tableau d'objets Ad
     */
    public function searchAds(string $query, ?int $idCategory = null, ?int $idPlatform = null, string $sort = 'date_desc', int $page = 1): array
    {   
        // Nettoyage de la recherche texte
        $cleanedQuery = htmlspecialchars(trim($query)); // MODIFICATION : Sanitize la recherche
        
        // Calcul de la pagination ( ex : 12 annonces par page)
        $limit = 12;
        // Si je suis page 1, offset = 0. Si page 2, offset = 12, etc.
        $offset = ($page- 1) * $limit;

        // Je récupère les annonces pour cette page précise
        $ads = $this->adRepository->searchAndFilter($cleanedQuery, $idCategory, $idPlatform, $sort, $limit, $offset);

        // Je compte le total d'annonces existantes
        $totalAds = $this->adRepository->countAdsWithFilters($cleanedQuery, $idCategory, $idPlatform);

        // Je calcule le nombre total de pages (ceil arrondi à l'entier supérieur)
        $totalPages = ceil($totalAds / $limit);

        // Je renvoie un "package" complet au Contrôleur
        return [
            'ads' => $ads,
            'totalPages' => max(1, (int)$totalPages) // Au minimum 1 page, même si 0 annonce
        ];
    }

    /**
     * Retourne le nombre d'annonces actuellement en ligne (disponibles)
     */
    public function countActiveAds(): int {
        return $this->adRepository->countAdsByStatus('disponible');
    }

    /**
     * Retourne le nombre d'annonces vendues (ventes réalisées)
     */
    public function countSoldAds(): int {
        return $this->adRepository->countAdsByStatus('vendu');
    }

    // =========================================================
    // SECTION : MISE À JOUR (UPDATE)
    // =========================================================

    /**
     * Traite la modification d'une annonce
     */

    public function updateAdData(int $adId, array $postData, int $userId): void
    {
        // Je récupère l'annonce pour verifier le propriétaire
        $ad = $this->getAdById($adId);

        if ($ad->getUser()->getIdUser() !== $userId) {
            throw new Exception("Action refusée : Vous ne pouvez modifier que vos propres annonces.");
        }

        // Nettoyage des données
        $cleanTitle = htmlspecialchars(trim($postData['title'] ?? ''));
        $cleanDesc = htmlspecialchars(trim($postData['description'] ?? ''));

        if (empty($cleanTitle)) {
            throw new Exception("Le titre est obligatoire.");
        }

        $price = (float) ($postData['price'] ?? 0);
        if ($price < 0) {
            throw new Exception("Le prix ne peut pas être négatif.");
        }

        // Je modifie directement l'objet $ad que j'ai récupéré au début de la fonction
        $ad->setTitle($cleanTitle);
        $ad->setDescription($cleanDesc);
        $ad->setPrice($price);
        $ad->setIdCategory((int) $postData['id_category']);
        $ad->setIdPlatform((int) $postData['id_platform']);

        // J'envoie l'objet complet au Repository
        $success = $this->adRepository->updateAdInfo($ad);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la mise à jour de l'annonce.");
        }
    }

    // =========================================================
    // SECTION : SUPPRESSION (DELETE)
    // =========================================================

    /**
     * Supprime une annonce après avoir vérifié les droits de l'utilisateur
     * @param int $adId L'ID de l'annonce
     * @param int $userId L'ID de l'utilisateur qui demande la suppression
     * @throws Exception Si l'annonce n'existe pas ou si l'utilisateur n'est pas le propriétaire
     */
    public function deleteAd(int $adId, int $userId): void
    {
        // Je récupère l'annonce
        $ad = $this->getAdById($adId);

        // Est-ce que celui qui clique est bien le vendeur ?
        if ($ad->getUser()->getIdUser() !== $userId) {
            throw new Exception("Action refusée : Vous n'êtes pas le propriétaire de cette annonce.");
        }

        // Si tout es bon, je demande au Repositry de détruire la ligne en BDD
        $success = $this->adRepository->delete($adId);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la suppression de l'annonce.");
        }
    }

    // =========================================================
    // SECTION : ADMINISTRATION (MODÉRATION)
    // =========================================================

    /**
     * Récupère la liste complète de toutes les annonces pour le Back-Office
     * @return array Un tableau d'objets Ad
     */
    public function getAllAdsForAdmin(): array
    {
        return $this->adRepository->findAllWithDetails();
    }

    /**
     * Supprime une annonce (Modération Admin) sans vérifier le propriétaire
     * @param int $adId L'ID de l'annonce
     * @throws Exception Si l'annonce n'existe pas ou si la suppression échoue
     */
    public function deleteAdAsAdmin(int $adId): void
    {
        // Je vérifie simplement que l'annonce existe
        $this->getAdById($adId);

        // Je lance la suppression directe
        $success = $this->adRepository->delete($adId);

        if (!$success) {
            throw new Exception("Une erreur est survenue lors de la suppression de l'annonce par la modération.");
        }
    }
}
?>