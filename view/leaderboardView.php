<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement — MonkeyStats</title>
    <link rel="stylesheet" href="css/leaderboard.css">
</head>
<body>
    <a href="#contenu" class="skip-link">Aller au contenu</a>

    <div class="container">
        <header>
            <h1>classement mondial</h1>
            <p>top 50 — anglais</p>

            <nav class="time-selector" aria-label="Filtre par durée">
                <a href="index.php?action=leaderboard&time=15"
                   <?= $mode == 15 ? 'class="active" aria-current="page"' : '' ?>>15s</a>
                <a href="index.php?action=leaderboard&time=60"
                   <?= $mode == 60 ? 'class="active" aria-current="page"' : '' ?>>60s</a>
            </nav>
        </header>

        <main id="contenu">
            <?php if (!empty($leaderboard)): ?>
                <table>
                    <caption>Top 50 mondial — mode <?= (int)$mode ?>s, anglais</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="rank">#</th>
                            <th scope="col">pseudo</th>
                            <th scope="col">wpm</th>
                            <th scope="col">précision</th>
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
                <p class="empty-message">Le classement est vide ou le chargement a échoué.</p>
            <?php endif; ?>
        </main>

        <footer>
            <nav aria-label="Navigation">
                <a href="index.php">← retour</a>
            </nav>
        </footer>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
