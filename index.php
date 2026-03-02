<?php
$message = "";
$messageType = "";

/* =========================
   Načtení JSON souboru
========================= */

$jsonData = file_get_contents('profile.json');

if ($jsonData === false) {
    die('Soubor profile.json nebyl nalezen.');
}

$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Chyba v JSON: ' . json_last_error_msg());
}

$interests = $data["interests"] ?? [];

/* =========================
   Zpracování formuláře
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["new_interest"])) {

        $newInterest = trim($_POST["new_interest"]);

        if (empty($newInterest)) {
            $message = "Pole nesmí být prázdné.";
            $messageType = "error";
        } else {

            // Case-insensitive kontrola duplicit
            $lowerInterests = array_map('mb_strtolower', $interests);

            if (in_array(mb_strtolower($newInterest), $lowerInterests)) {
                $message = "Tento zájem už existuje.";
                $messageType = "error";
            } else {

                $interests[] = $newInterest;
                $data["interests"] = $interests;

                $saved = file_put_contents(
                    "profile.json",
                    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    LOCK_EX
                );

                if ($saved !== false) {
                    $message = "Zájem byl úspěšně přidán.";
                    $messageType = "success";
                } else {
                    $message = "Chyba při ukládání souboru.";
                    $messageType = "error";
                }
            }
        }
    }
}

/* =========================
   Session ID (jen vizuální)
========================= */

$sessionId = strtoupper(substr(md5(uniqid()), 0, 9));

?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>root@antonin:~/portfolio</title>
    <link rel="stylesheet" href="css.css">
</head>

<body>

<header class="site-header">
    <div class="container header-inner">
        <h1 class="site-title">
            &gt; ./root/home/<?php echo htmlspecialchars(strtolower(str_replace(' ', '_', $data['name'] ?? 'user'))); ?>
        </h1>
    </div>
</header>

<main class="container main-grid">

    <!-- PROFIL -->
    <aside class="profile terminal-box">
        <div class="avatar-container">
            <div class="avatar">
                <?php
                $initials = explode(" ", $data['name'] ?? '');
                $first = $initials[0][0] ?? '';
                $second = $initials[1][0] ?? '';
                echo htmlspecialchars($first . $second);
                ?>
            </div>
        </div>

        <h2 class="name"><?php echo htmlspecialchars($data['name'] ?? ''); ?></h2>
        <p class="role">&lt; <?php echo htmlspecialchars($data['role'] ?? ''); ?> /&gt;</p>

        <ul class="meta-list">
            <li><span class="prompt">$</span> loc: <?php echo htmlspecialchars($data['location'] ?? ''); ?></li>
            <li><span class="prompt">$</span> status: <span class="blink">ONLINE</span></li>
        </ul>
    </aside>

    <!-- SKILLS -->
    <section class="terminal-box skills">
        <div class="terminal-content">
            <ul class="skills-list">
                <?php foreach ($data['skills'] ?? [] as $skill): ?>
                    <li>
                        <span class="prompt">&gt;</span>
                        <?php echo htmlspecialchars($skill); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- INTERESTS -->
    <section class="terminal-box interests">
        <div class="terminal-content">

            <h3>$ cat interests.txt</h3>

            <?php foreach ($interests as $interest): ?>
                <p>
                    <span class="prompt">$</span>
                    <?php echo htmlspecialchars($interest); ?>
                </p>
            <?php endforeach; ?>

            <!-- HLÁŠKA -->
            <?php if (!empty($message)): ?>
                <p class="<?php echo htmlspecialchars($messageType); ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <!-- FORMULÁŘ -->
            <form method="POST">
                <input type="text" name="new_interest" placeholder="Nový zájem..." required>
                <button type="submit">Přidat zájem</button>
            </form>

        </div>
    </section>

    <!-- PROJECTS -->
    <section class="terminal-box projects">
        <div class="terminal-content">
            <?php foreach ($data['projects'] ?? [] as $project): ?>
                <div class="project">
                    <p>
                        <span class="prompt">[PROJECT]</span>
                        <strong><?php echo htmlspecialchars($project['title'] ?? ''); ?></strong>
                    </p>
                    <p><?php echo htmlspecialchars($project['description'] ?? ''); ?></p>
                    <p>
                        Link:
                        <a href="<?php echo htmlspecialchars($project['link'] ?? '#'); ?>" target="_blank">
                            <?php echo htmlspecialchars($project['link'] ?? ''); ?>
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<footer class="site-footer">
    <small>
        Session ID: <?php echo $sessionId; ?> |
        © 2026 <?php echo htmlspecialchars($data['name'] ?? ''); ?>
    </small>
</footer>

</body>
</html>