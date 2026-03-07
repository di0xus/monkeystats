<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($userStats['name']) ? htmlspecialchars($userStats['name']) : 'User' ?> - MonkeyStats</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="container">
        <?php if (isset($userStats['error'])): ?>
            <div class="error">
                <h1>user not found</h1>
                <p><?= htmlspecialchars($userStats['error']) ?></p>
                <div class="nav">
                    <a href="index.php">retour</a>
                </div>
            </div>
        <?php else: ?>
            <div class="profile-header">
                <div class="avatar">
                    <?php if ($userStats['discord_name']): ?>
                        <?php if ($userStats['discord_avatar']): ?>
                            <img src="https://cdn.discordapp.com/avatars/<?= $userStats['discord_name'] ?>/<?= $userStats['discord_avatar'] ?>.png" alt="Avatar">
                        <?php else: ?>
                            <img src="https://cdn.discordapp.com/embed/avatars/<?= ($userStats['discord_name'] >> 22) % 6 ?>.png" alt="Default Avatar">
                        <?php endif; ?>
                    <?php else: ?>
                        <?= strtoupper(substr($userStats['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div class="user-details">
                    <h1><?= htmlspecialchars($userStats['name']) ?></h1>
                    <div class="user-meta">
                        Level <?= $userStats['level'] ?> &nbsp;•&nbsp; <?= number_format($userStats['xp']) ?> XP 
                        <?php if($userStats['discord_name']): ?> &nbsp;•&nbsp; Discord lié<?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="group-title">Statistiques</div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">tests started</span>
                    <span class="stat-val"><?= number_format($userStats['tests_completed'] ?? 0) ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">time typing</span>
                    <span class="stat-val"><?= round(($userStats['time_typing'] ?? 0) / 3600, 1) ?>h</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">avg speed (15/60s)</span>
                    <span class="stat-val"><?= round((($userStats['wpm_15'] ?? 0) + ($userStats['wpm_60'] ?? 0)) / 2) ?></span>
                </div>
            </div>

            <div class="group-title">Personal Bests</div>
            <div class="pb-grid">
                <div class="pb-box">
                    <h3>15 seconds</h3>
                    <div class="pb-large"><?= round($userStats['wpm_15'] ?? 0) ?></div>
                    <div class="pb-small">acc: <?= round($userStats['acc_15'] ?? 0) ?>%</div>
                </div>
                <div class="pb-box">
                    <h3>60 seconds</h3>
                    <div class="pb-large"><?= round($userStats['wpm_60'] ?? 0) ?></div>
                    <div class="pb-small">acc: <?= round($userStats['acc_60'] ?? 0) ?>%</div>
                </div>
            </div>

            <div class="nav">
                <a href="index.php">nouvelle recherche</a>
                <a href="index.php?action=leaderboard">leaderboard</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>