<?php
namespace App\Controller;

/**
 * Contrôleur gérant les pages statiques et légales du site.
 */
class LegalController extends BaseController {

    public function cgv(?array $parms): void
    {
        include __DIR__ . '/../../template/cgv.php';
    }

    public function privacy(?array $params): void
    {
        include __DIR__ . '/../../template/privacy.php';
    }
}
?>