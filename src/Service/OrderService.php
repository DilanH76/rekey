<?php
namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\AdRepository;
use App\Entity\Order;
use \Exception;
use \DateTime;
use \PDO;

/**
 * Service gérant la logique métier des commandes (transactions)
 */
class OrderService {

    private OrderRepository $orderRepository;
    private AdRepository $adRepository;
    private PDO $pdo; // PDO pour gérer les transactions

    /**
     * Constructeur avec injection de dépendances
     */
    public function __construct(OrderRepository $orderRepository, AdRepository $adRepository, PDO $pdo)
    {
        $this->orderRepository = $orderRepository;
        $this->adRepository = $adRepository;
        $this->pdo = $pdo; // Injection
    }

    // =========================================================
    // SECTION : TRAITEMENT DE L'ACHAT
    // =========================================================

    public function processCheckout(int $adId, int $userId): void
    {
        // Je récupère l'annonce
        $ad = $this->adRepository->findByIdWithDetails($adId);

        if (!$ad) {
            throw new Exception("Cette annonce n'existe plus.");
        }

        // L'annonce est-elle toujours disponible ?
        if ($ad->getStatus() !== 'disponible') {
            throw new Exception("Désolé, ce jeu vient juste d'être vendu à un autre utilisateur.");
        }

        // L'utilisateur essaie-t-il d'acheter sa propre annonce ?
        if ($ad->getUser()->getIdUser() === $userId) {
            throw new Exception("Action refusée : Vous ne pouvez pas acheter votre propre jeu.");
        }

        // Génération d'une référence de commande unique
        $reference = 'CMD-'. date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $order = new Order(
            $reference,
            new DateTime(),
            $adId,
            $userId
        );

        // Lancement de la transaction SQL (Tout ou Rien)
        try {
            $this->pdo->beginTransaction();

            // Je crée la commande
            $successOrder = $this->orderRepository->create($order);
            if (!$successOrder) {
                throw new Exception("Erreur lors de la validation du paiement.");
            }

            // Je marque l'annonce comme vendue
            $successAd = $this->adRepository->markAsSold($adId);
            if (!$successAd) {
                throw new Exception("Erreur lors de la mise à jour de l'annonce.");
            }

            // Si j'arrive ici sans erreur, je valide définitivement tout dans la BDD
            $this->pdo->commit();

        } catch (Exception $e) {
            // Si la moindre chose a planté (ex: faille de concurrence, erreur SQL), 
            // j'annule TOUT (la commande n'est pas sauvegardée)
            $this->pdo->rollBack();
            
            // je relance l'exception pour que le contrôleur affiche le message d'erreur
            throw new Exception("La transaction a échoué : " . $e->getMessage());
        }
    }

    // =========================================================
    // SECTION : LECTURE
    // =========================================================

    /**
     * Récupère l'historique des achats d'un utilisateur
     * @param int $userId L'ID de l'utilisateur
     * @return array Liste des commandes
     */
    public function getUserPurchases(int $userId): array
    {
        return $this->orderRepository->findByUserId($userId);
    }
}
?>