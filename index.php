<?php
require_once __DIR__ . '/controller/LeaderboardController.php';
require_once __DIR__ . '/controller/UserController.php';

// var_dump($_GET); // debug

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'leaderboard':
        $controller = new LeaderboardController();
        $controller->showLeaderboard();
        break;
        
    case 'search':
        $controller = new UserController();
        $controller->search();
        break;
        
    default:
        $controller = new UserController();
        $controller->home();
        break;
}
?>