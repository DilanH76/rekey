<?php
session_start();

// ROUTEUR DYNAMIQUE

    require_once __DIR__.'/../vendor/autoload.php';

    use App\Controller\{
        E404Controller,
        HomeController,
        AuthController,
        ProfileController,
        AdController
    };
    use App\Service\{
        DatabaseFactory,
        AuthService,
        ProfileService,
        AdService
    };
    use App\Repository\{
        UserRepository,
        AdRepository,
        CategoryRepository,
        PlatformRepository
    };

    $request = trim($_SERVER['REQUEST_URI'], '/');
    $params = explode('/', $request);
    $controller=array_shift($params);
    $method=array_shift($params);
    
    if ($controller=='') {$controller='Home';}
    if ($method=='') {$method='index';}
    
    $controllerClass = 'App\\Controller\\'. ucfirst($controller) . 'Controller';
    if (!class_exists($controllerClass)) {
        $controllerClass = E404Controller::class;
    }

    // Connexion à PDO
    try {
        $envPath = __DIR__ . '/../.env';
        // si pas de fichier .env on lève une exception
        if (!file_exists($envPath)) {
            throw new Exception("Configuration file (.env) is missing at project root.");
        }
        // Lecture du .env
        $config = parse_ini_file($envPath);
        // La factory DatabaseFactory crée le $pdo à partir du contenu de .env
        $pdo = DatabaseFactory::create($config);
    } catch (Exception $e) {
        error_log("Connection failed: " . $e->getMessage());
        die("Une erreur technique est survenue. Veuillez réessayer plus tard.");
    }

    // Définition des "recettes" de controlleurs (Conteneur d'Injection de Dépendances)
    $container = [
        E404Controller::class => function($pdo) {
            return new E404Controller();
        },
        HomeController::class => function($pdo) {
            return new HomeController();
        },
        AuthController::class => function($pdo) {
            $userRepository = new UserRepository($pdo);
            $authService = new AuthService($userRepository);
            return new AuthController($authService);
        },
        ProfileController::class => function($pdo) {
            $userRepository = new UserRepository($pdo);
            $profileService = new ProfileService($userRepository);
            return new ProfileController($profileService);
        },
        AdController::class => function($pdo) {
            $adRepository = new AdRepository($pdo);
            $categoryRepository = new CategoryRepository($pdo);
            $platformRepository = new PlatformRepository($pdo);
            
            $adService = new AdService($adRepository, $categoryRepository, $platformRepository);
            
            return new AdController($adService);
        }
    ];

    // Instanciation du controlleur
    if (!isset($container[$controllerClass])) {
        // Sécurité si une classe existe mais n'est pas dans le conteneur
        $controllerClass = E404Controller::class;
    }
    $controllerInstance = $container[$controllerClass]($pdo);
    
    // Appel de la méthode (parametre d'URL numero 2 ou par defaut index)
    if (!method_exists($controllerInstance, $method)) {
        $method = 'index'; // si erreur de méthode on redirige vers index
    }
    $controllerInstance->$method($params);

?>