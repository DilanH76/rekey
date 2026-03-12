<?php 
namespace App\Repository;

use App\Entity\Order;
use App\Entity\Ad;
use App\Entity\Platform;
use \PDO;
use \DateTime;

/**
 * Repository gérant toutes les requêtes SQL liées à la table 'orders'
 */
class OrderRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =========================================================
    // SECTION : CRÉATION (CREATE)
    // =========================================================

    /**
     * Sauvegarde une nouvelle commande en base de données
     * @param Order $order L'objet commande contenant les infos
     * @return bool True si l'insertion a réussi, False sinon
     */
    public function create(Order $order): bool
    {
        $sql = "INSERT INTO orders (reference, date_order, id_ads, id_user)
                VALUES (:reference, :date_order, :id_ads, :id_user)";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':reference', $order->getReference());
        $stmt->bindValue(':date_order', $order->getDateOrder()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id_ads', $order->getIdAds(), PDO::PARAM_INT);
        $stmt->bindValue(':id_user', $order->getIdUser(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    // =========================================================
    // SECTION : LECTURE (READ)
    // =========================================================

    /**
     * Récupère toutes les commandes (achats) d'un utilisateur spécifique,
     * en joignant les informations du jeu (Ad) pour pouvoir afficher la clé.
     * @param int $userId L'identifiant de l'acheteur
     * @return array Un tableau d'objets Order complètement hydratés
     */
    public function findByUserId(int $userId): array
    {
        $sql ="SELECT
                    o.*,
                    a.title, a.description, a.price,a.cover_image, a.game_key, a.status, a.created_at AS ad_created_at, a.id_category, a.id_user AS seller_id,
                    p.label AS platform_label, p.icon_svg AS platform_icon, p.id_platform
                FROM orders o
                INNER JOIN ads a ON o.id_ads = a.id_ads
                INNER JOIN platforms p ON a.id_platform = p.id_platform
                WHERE o.id_user = :id_user
                ORDER BY o.date_order DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $userId]);

        $orders = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // Je crée l'objet Order de base
            $order = new Order(
                $row['reference'],
                new DateTime($row['date_order']),
                (int) $row['id_ads'],
                (int) $row['id_user'],
                (int) $row['id_order']
            );

            $ad = new Ad(
                $row['title'],
                $row['description'],
                (float) $row['price'],
                $row['cover_image'],
                $row['game_key'],
                $row['status'],
                new \DateTime($row['ad_created_at']),
                (int) $row['id_platform'],
                (int) $row['id_category'],
                (int) $row['seller_id'],
                (int) $row['id_ads']
            );
            // Je recrée la plateforme pour l'icône
            $platform = new Platform(
                $row['platform_icon'],
                $row['platform_label'],
                (int) $row['id_platform']
            );
            $ad->setPlatform($platform);
            // J'imbrique l'annonce dans la commande
            $order->setAd($ad);

            // j'ajoute la commande final
            $orders[] = $order;

        }

        return $orders;
    }
}
?>