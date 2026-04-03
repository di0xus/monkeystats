<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MonkeyStats</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <a href="#contenu" class="skip-link">Aller au contenu</a>

    <main id="contenu" class="container">
        <h1>monkey<span class="accent">stats</span></h1>
        <p class="subtitle">consulte les stats de tes amis sur monkeytype</p>

        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="search">
            <label for="pseudo" class="visually-hidden">Pseudo Monkeytype</label>
            <input
                type="text"
                id="pseudo"
                name="username"
                list="suggestions"
                placeholder="entre un pseudo..."
                autocomplete="off"
                required
                autofocus
            >
            <datalist id="suggestions">
                <?php foreach ($suggestions as $name): ?>
                    <option value="<?= htmlspecialchars($name) ?>">
                <?php endforeach; ?>
            </datalist>
            <button type="submit">rechercher</button>
        </form>

        <nav class="liens" aria-label="Navigation">
            <a href="index.php?action=leaderboard">voir le classement</a>
        </nav>
    </main>

    <script src="js/app.js"></script>
</body>
</html>
