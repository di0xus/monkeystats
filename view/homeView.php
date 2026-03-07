<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MonkeyStats</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="container">
        <h1>monkey<span style="color: #e2b714">stats</span></h1>
        <div class="subtitle">lookup des profils monkeytype</div>
        
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="search">
            <input type="text" name="username" list="names" placeholder="entrer un username..." autocomplete="off" required autofocus>
            <datalist id="names">
                <?php foreach ($suggestions as $name): ?>
                    <option value="<?= htmlspecialchars($name) ?>">
                <?php endforeach; ?>
            </datalist>
            <button type="submit">chercher</button>
        </form>
        
        <div class="links">
            <a href="index.php?action=leaderboard">voir le leaderboard</a>
        </div>
    </div>
</body>
</html>
