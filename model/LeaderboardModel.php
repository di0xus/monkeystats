<?php
class LeaderboardModel {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function getLeaderboard($mode = 15) {
        if ($this->baseMySQLVide($mode)) {
            $url = "https://api.monkeytype.com/leaderboards?language=english&mode=time&mode2=" . $mode;
            
            $options = [
                'http' => [
                    'method' => "GET",
                    'header' => "User-Agent: SAE203/1.0\r\n"
                ]
            ];
            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['data']['entries'])) {
                    $this->saveLeaderboard($data['data']['entries'], $mode);
                }
            }
        }
        
        try {
            $query = "SELECT * FROM Leaderboard WHERE mode = :mode ORDER BY rank_pos ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['mode' => $mode]);
            $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $leaderboard;
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage());
        }
    }

    protected function baseMySQLVide($mode) {
        try {
            $query = "SELECT COUNT(*) FROM Leaderboard WHERE mode = :mode";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['mode' => $mode]);
            $count = $stmt->fetchColumn();

            return $count == 0;
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage());
        }
    }

    public function saveLeaderboard($entries, $mode) {
        try {
            $sql = "INSERT INTO Leaderboard (name, wpm, acc, rank_pos, mode) VALUES (:name, :wpm, :acc, :rank_pos, :mode)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($entries as $entry) {
                $stmt->execute([
                    'name' => $entry['name'] ?? 'Unknown',
                    'wpm' => $entry['wpm'] ?? 0,
                    'acc' => $entry['acc'] ?? 0,
                    'rank_pos' => $entry['rank'] ?? 0,
                    'mode' => $mode
                ]);
            }
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage());
        }
    }
}
?>