<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($userStats['name']) ? htmlspecialchars($userStats['name']) : 'Profil' ?> — MonkeyStats</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <a href="#contenu" class="skip-link">Aller au contenu</a>

    <div class="container">
        <?php if (isset($userStats['error'])): ?>
            <main id="contenu" class="erreur">
                <h1>utilisateur introuvable</h1>
                <p><?= htmlspecialchars($userStats['error']) ?></p>
                <nav aria-label="Navigation">
                    <a href="index.php">← retour</a>
                </nav>
            </main>
        <?php else: ?>
            <header class="profil-header">
                <div class="avatar" aria-hidden="true">
                    <?php if ($userStats['discord_name']): ?>
                        <?php if ($userStats['discord_avatar']): ?>
                            <img src="https://cdn.discordapp.com/avatars/<?= $userStats['discord_name'] ?>/<?= $userStats['discord_avatar'] ?>.png?size=256"
                                 alt="avatar de <?= htmlspecialchars($userStats['name']) ?>">
                        <?php else: ?>
                            <img src="https://cdn.discordapp.com/embed/avatars/<?= ($userStats['discord_name'] >> 22) % 6 ?>.png"
                                 alt="avatar par défaut">
                        <?php endif; ?>
                    <?php else: ?>
                        <span aria-hidden="true"><?= strtoupper(substr($userStats['name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <h1
                        id="username-heading"
                        tabindex="0"
                        role="button"
                        aria-label="<?= htmlspecialchars($userStats['name']) ?> — cliquer pour copier"
                    ><?= htmlspecialchars($userStats['name']) ?></h1>
                    <p class="user-meta">
                        Niveau <?= $userStats['level'] ?> &nbsp;•&nbsp; <?= number_format($userStats['xp']) ?> XP
                        <?php if ($userStats['discord_name']): ?>&nbsp;•&nbsp; Discord lié<?php endif; ?>
                    </p>
                </div>
            </header>

            <main id="contenu">
                <section class="stats-section" aria-labelledby="titre-stats">
                    <h2 id="titre-stats">Statistiques</h2>
                    <ul class="stats-grid" role="list">
                        <li class="stat-item">
                            <span class="stat-label">tests effectués</span>
                            <span class="stat-val"><?= number_format($userStats['tests_completed'] ?? 0) ?></span>
                        </li>
                        <li class="stat-item">
                            <span class="stat-label">temps de frappe</span>
                            <span class="stat-val"><?= round(($userStats['time_typing'] ?? 0) / 3600, 1) ?>h</span>
                        </li>
                        <li class="stat-item">
                            <span class="stat-label">vitesse moy.</span>
                            <span class="stat-val"><?= round((($userStats['wpm_15'] ?? 0) + ($userStats['wpm_60'] ?? 0)) / 2) ?></span>
                        </li>
                    </ul>
                </section>

                <section class="stats-section" aria-labelledby="titre-pb">
                    <h2 id="titre-pb">Meilleurs scores</h2>
                    <div class="pb-grid">
                        <div class="pb-box">
                            <h3>15 secondes</h3>
                            <div class="pb-large" aria-label="<?= round($userStats['wpm_15'] ?? 0) ?> mots par minute">
                                <?= round($userStats['wpm_15'] ?? 0) ?>
                            </div>
                            <p class="pb-small">précision : <?= round($userStats['acc_15'] ?? 0) ?>%</p>
                        </div>
                        <div class="pb-box">
                            <h3>60 secondes</h3>
                            <div class="pb-large" aria-label="<?= round($userStats['wpm_60'] ?? 0) ?> mots par minute">
                                <?= round($userStats['wpm_60'] ?? 0) ?>
                            </div>
                            <p class="pb-small">précision : <?= round($userStats['acc_60'] ?? 0) ?>%</p>
                        </div>
                    </div>
                </section>
            </main>

            <footer>
                <nav class="page-nav" aria-label="Navigation">
                    <a href="index.php">nouvelle recherche</a>
                    <a href="index.php?action=leaderboard">classement</a>
                </nav>
            </footer>
        <?php endif; ?>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
