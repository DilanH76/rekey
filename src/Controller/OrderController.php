<?php
namespace App\Controller;

use App\Service\OrderService;
use App\Service\AdService;
use \Exception;

/**
 * Contrôleur gérant le tunnel d'achat et l'historique des commandes
 */
class OrderController {

    private OrderService $orderService;
    private AdService $adService;

    public function __construct(OrderService $orderService, AdService $adService)
    {
        $this->orderService = $orderService;
        $this->adService= $adService;
    }

    public function index(?array $params) {
        header('Location: /Home');
        exit;
    }

    // =========================================================
    // 1. AFFICHER LE TUNNEL D'ACHAT (CHECKOUT)
    // =========================================================
    /**
     * URL : /Order/checkout/5
     */
    public function checkout(?array $params) {
        // Utilisateur connecté uniquement
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Vous devez être connecté pour acheter un jeu.'
            ];
            header('Location: /Auth/login');
            exit;
        }

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            $ad = $this->adService->getAdById($adId);

            // Vérification avant l'affichage
            if ($ad->getStatus() !== 'disponible') {
                throw new Exception("Ce jeu n'est plus disponible");
            }
            if ($ad->getUser()->getIdUser() === $_SESSION['user_id']) {
                throw new Exception("Vous ne pouvez pas acheter votre propre jeu !");
            }

            include __DIR__ . '/../../template/checkout.php';

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Home');
            exit;
        }
    }

    // =========================================================
    // 2. TRAITER LE PAIEMENT
    // =========================================================
    /**
     * URL : /Order/process/5
     */
    public function process(?array $params) {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Home');
            exit;
        }

        $adId = isset($params[0]) ? (int)$params[0] : 0;

        try {
            // je lance le gros traitement de métier
            $this->orderService->processCheckout($adId, $_SESSION['user_id']);

            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => 'Paiement validé ! Voici votre clé d\'activation.'
            ];
            header('Location: /Order/myPurchases');
            exit;

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $err->getMessage()
            ];
            header('Location: /Order/checkout/'.$adId);
            exit;
        }
    }

    // =========================================================
    // 3. AFFICHER L'HISTORIQUE DES ACHATS
    // =========================================================
    /**
     * URL : /Order/myPurchases
     */
    public function myPurchases(?array $params) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Auth/login');
            exit;
        }

        try {
            // Je demande l'historique au service
            $orders = $this->orderService->getUserPurchases($_SESSION['user_id']);

            include __DIR__ . '/../../template/my_purchases.php';

        } catch (Exception $err) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Erreur lors du chargement de vos achats."
            ];
            header('Location: /Profile');
            exit;
        }
    }
}
?>