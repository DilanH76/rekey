<?php
namespace App\Tests;

use App\Service\AdService;
use App\Repository\AdRepository;
use App\Repository\CategoryRepository;
use App\Repository\PlatformRepository;
use PHPUnit\Framework\TestCase;
use \Exception;

class AdServiceTest extends TestCase
{
    public function testCreateAdThrowsExceptionIfPriceIsNegative(): void
    {
        // Preparation
        // Le AdService à besoin de 3 Repositories pour être insatancié.
        // Je crée des "Mocks" (des doublons vides) car je ne veut pas toucher à la vraie Base de Données.
        $adRepoMock = $this->createMock(AdRepository::class);
        $catRepoMock = $this->createMock(CategoryRepository::class);
        $platRepoMock = $this->createMock(PlatformRepository::class);

        // J'instancie le service avec mes faux repositories
        $adService = new AdService($adRepoMock, $catRepoMock, $platRepoMock);

        // Je simule ce qu'un utilisateur malveillant enverrait dans me $_POST
        $postData = [
            'title' => 'Cyberpunk',
            'game_key' => 'ABCD-1234',
            'price' => -15.50 // Prix négatif
        ];
        $filesData = []; // Pas besoin d'image pour ce test
        $userId = 1;

        // Attente (expect)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le prix ne peut pas être négatif.");

        // Action
        // Je lance la méthode
        $adService->createAd($postData, $filesData, $userId);
    }
}

?>