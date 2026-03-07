<?php
require_once __DIR__ . '/../model/LeaderboardModel.php';

class LeaderboardController {
    public function showLeaderboard() {
        $mode = isset($_GET['time']) && $_GET['time'] == '60' ? 60 : 15;
        
        $leaderboardModel = new LeaderboardModel();
        $leaderboard = $leaderboardModel->getLeaderboard($mode);

        require_once __DIR__ . '/../view/leaderboardView.php';
    }
}
?>