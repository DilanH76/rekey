<?php
namespace App\Controller;

/**
 * Contrôleur de base dont héritent les autres contrôleurs.
 * Centralise les méthodes de sécurité et de vérification.
 */
abstract class BaseController {

    /**
     * Vérifie que l'utilisateur est connecté.
     * @param string $redirect L'URL de redirection si non connecté
     */
    protected function requireAuth(string $redirect = '/Auth/login'): void 
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: $redirect");
            exit;
        }
    }

    /**
     * Vérifie que l'utilisateur est connecté ET qu'il est administrateur.
     */
    protected function requireAdmin(): void 
    {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Accès refusé. Privilèges insuffisants.'
            ];
            header('Location: /Home');
            exit;
        }
    }

    /**
     * Vérifie que la requête est bien de type POST (Soumission de formulaire).
     * @param string $redirect L'URL de redirection si ce n'est pas un POST
     */
    protected function requirePost(string $redirect = '/Home'): void 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: $redirect");
            exit;
        }
    }
}
?>