<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function getUserStats($username) {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE name = :name");
        $stmt->execute(['name' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || (time() - strtotime($user['last_updated'])) > 600) {
            $url = "https://api.monkeytype.com/users/" . urlencode($username) . "/profile";
            
            $options = [
                'http' => [
                    'header' => "User-Agent: SAE203/1.0\r\n" .
                                "Authorization: ApeKey " . MONKEYTYPE_APE_KEY . "\r\n",
                    'ignore_errors' => true
                ]
            ];
            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            $status_line = $http_response_header[0];
            preg_match('{HTTP\/\S+\s+(\d+)}', $status_line, $match);
            $status = $match[1];

            if ($status == 404) {
                return ['error' => "Joueur introuvable sur Monkeytype."];
            } elseif ($status != 200) {
                return ['error' => "Erreur avec l'API... (Code $status)."];
            }

            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['data'])) {
                    $p = $data['data'];
                    
                    $pb15 = $p['personalBests']['time']['15'][0] ?? ['wpm' => 0, 'acc' => 0];
                    $pb60 = $p['personalBests']['time']['60'][0] ?? ['wpm' => 0, 'acc' => 0];
                    
                    $stats = [
                        'name' => $p['name'],
                        'tests_completed' => $p['typingStats']['completedTests'] ?? 0,
                        'time_typing' => $p['typingStats']['timeTyping'] ?? 0,
                        'wpm_15' => $pb15['wpm'] ?? 0,
                        'acc_15' => $pb15['acc'] ?? 0,
                        'wpm_60' => $pb60['wpm'] ?? 0,
                        'acc_60' => $pb60['acc'] ?? 0,
                        'xp' => $p['xp'] ?? 0,
                        'level' => isset($p['xp']) ? max(1, floor(sqrt($p['xp']) / 5)) : 1,
                        'discord_name' => $p['discordId'] ?? null,
                        'discord_avatar' => $p['discordAvatar'] ?? null,
                        'badge_id' => $p['badgeId'] ?? null
                    ];

                    $this->saveUser($stats);
                    return $stats;
                }
            }
            return $user ? $user : ['error' => "Impossible de récupérer les données."];
        }

        return $user;
    }

    private function saveUser($s) {
        $sql = "INSERT INTO Users (name, tests_completed, time_typing, wpm_15, acc_15, wpm_60, acc_60, xp, level, discord_name, discord_avatar, badge_id) 
                VALUES (:name, :tests, :time, :w15, :a15, :w60, :a60, :xp, :lvl, :disc, :avatar, :badge) 
                ON DUPLICATE KEY UPDATE 
                    tests_completed = :tests, 
                    time_typing = :time, 
                    wpm_15 = :w15, acc_15 = :a15, 
                    wpm_60 = :w60, acc_60 = :a60,
                    xp = :xp, level = :lvl,
                    discord_name = :disc, 
                    discord_avatar = :avatar,
                    badge_id = :badge,
                    last_updated = CURRENT_TIMESTAMP";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $s['name'],
            'tests' => $s['tests_completed'],
            'time' => $s['time_typing'],
            'w15' => $s['wpm_15'],
            'a15' => $s['acc_15'],
            'w60' => $s['wpm_60'],
            'a60' => $s['acc_60'],
            'xp' => $s['xp'],
            'lvl' => $s['level'],
            'disc' => $s['discord_name'],
            'avatar' => $s['discord_avatar'],
            'badge' => $s['badge_id']
        ]);
    }
}
?>