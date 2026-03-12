<?php
namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\AdRepository;
use App\Entity\Order;
use \Exception;
use \DateTime;

/**
 * Service gérant la logique métier des commandes (transactions)
 */
class OrderService {

    private OrderRepository $orderRepository;
    private AdRepository $adRepository;

    /**
     * Constructeur avec injection de dépendances
     * J'ai besoin des deux repositories : pour lire/modifier l'annonce, et écrire la commande
     */
    public function __construct(OrderRepository $orderRepository, AdRepository $adRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->adRepository = $adRepository;
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

        // Génération d'une référence de commande unique ( ex : CMD-20260312-A8F4)
        // La fonction uniqid() génère une chaîne aléatoire unique.
        $reference = 'CMD-'. date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $order = new Order(
            $reference,
            new DateTime(),
            $adId,
            $userId
        );

        $successOrder = $this->orderRepository->create($order);

        if (!$successOrder) {
            throw new Exception("Une erreur est survenue lors de la validation du paiement.");
        }

        // Je met à jour en base de données
        $successAd = $this->adRepository->markAsSold($adId);

        if (!$successAd) {
            throw new Exception("La commande est passée mais le statut de l'annonce n'a pas pu être mis à jour.");
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