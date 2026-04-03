<?php
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../model/LeaderboardModel.php';

class UserController {
    public function home() {
        $lbModel = new LeaderboardModel();
        $leaderboard = $lbModel->getLeaderboard();
        $suggestions = array_column($leaderboard, 'name');

        require_once __DIR__ . '/../view/homeView.php';
    }

    public function search() {
        if (isset($_GET['username']) && !empty($_GET['username'])) {
            $username = $_GET['username'];
            $userModel = new UserModel();
            $userStats = $userModel->getUserStats($username);

            require_once __DIR__ . '/../view/userStatsView.php';
        } else {
            header('Location: index.php');
        }
    }
}
?>