<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\OrderService;
use App\Repository\OrderRepository;
use App\Repository\AdRepository;
use App\Entity\Ad;
use App\Entity\User;
use \PDO;
use \Exception;

class OrderServiceTest extends TestCase
{
    public function testProcessCheckoutThrowsExceptionIfUserBuysOwnAd(): void
    {
        // ==========================================
        // PRÉPARATION (Arrange) 
        // ==========================================
        
        // Je mock les 3 dépendances requises par le OrderService
        $orderRepoMock = $this->createMock(OrderRepository::class);
        $adRepoMock = $this->createMock(AdRepository::class);
        $pdoMock = $this->createMock(PDO::class);

        // Création du faux Vendeur (User)
        $fakeSeller = $this->createMock(User::class);
        // Je "force" le faux vendeur à dire que son ID est 5
        $fakeSeller->method('getIdUser')->willReturn(5);

        // Création de la Fausse Annonce (Ad)
        $fakeAd = $this->createMock(Ad::class);
        // Je "force" l'annonce à dire qu'elle est disponible pour passer la première sécurité
        $fakeAd->method('getStatus')->willReturn('disponible');
        // J'attache mon faux vendeur à l'annonce
        $fakeAd->method('getUser')->willReturn($fakeSeller);

        // Configuration du Faux AdRepository
        // Quand le service va chercher l'annonce n°99, je lui renvoie ma fausse annonce piégée
        $adRepoMock->method('findByIdWithDetails')->willReturn($fakeAd);

        // j'instancie le service avec mes Mocks
        $orderService = new OrderService($orderRepoMock, $adRepoMock, $pdoMock);

        // ==========================================
        // ATTENTE (Expect)
        // ==========================================
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Action refusée : Vous ne pouvez pas acheter votre propre jeu.");

        // ==========================================
        // ACTION (Act)
        // ==========================================
        
        $adIdToBuy = 99; // L'annonce ciblée
        $buyerId = 5;    // L'ID de l'acheteur (Identique à l'ID du vendeur )

        // Le service devrais tomber dans le piège
        $orderService->processCheckout($adIdToBuy, $buyerId);
    }
}