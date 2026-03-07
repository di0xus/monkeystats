<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - MonkeyStats</title>
    <link rel="stylesheet" href="css/leaderboard.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>global leaderboard</h1>
            <p>top 50 - english</p>
            
            <div class="time-selector">
                <a href="index.php?action=leaderboard&time=15" class="<?= $mode == 15 ? 'active' : '' ?>">15s</a>
                <a href="index.php?action=leaderboard&time=60" class="<?= $mode == 60 ? 'active' : '' ?>">60s</a>
            </div>
        </div>

        <?php if (!empty($leaderboard)): ?>
            <table>
                <thead>
                    <tr>
                        <th class="rank">#</th>
                        <th>name</th>
                        <th>wpm</th>
                        <th>acc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaderboard as $entry): ?>
                        <tr>
                            <td class="rank"><?= htmlspecialchars($entry['rank_pos']) ?></td>
                            <td>
                                <a class="name" href="index.php?action=search&username=<?= urlencode($entry['name']) ?>">
                                    <?= htmlspecialchars($entry['name']) ?>
                                </a>
                            </td>
                            <td class="val"><?= htmlspecialchars($entry['wpm']) ?></td>
                            <td><?= htmlspecialchars($entry['acc']) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#646669;">Leaderboard is empty or loading failed.</p>
        <?php endif; ?>

        <div class="nav">
            <a href="index.php">← retour</a>
        </div>
    </div>
</body>
</html>