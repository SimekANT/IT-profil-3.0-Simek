<?php
$jsonData = file_get_contents('profile.json');

if ($jsonData === false) {
    die('Soubor profile.json nebyl nalezen.');
}

$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Chyba v JSON: ' . json_last_error_msg());
}

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
<div class="terminal-overlay"></div>
<div class="scanline"></div>

<header class="site-header">
    <div class="container header-inner">
        <h1 class="site-title">&gt; ./root/home/<?php echo htmlspecialchars(strtolower(str_replace(' ', '_', $data['name']))); ?></h1>
    </div>
</header>

<main class="container main-grid">

    <aside class="profile terminal-box">
        <div class="avatar-container">
            <div class="avatar">
                <?php 
                $initials = explode(" ", $data['name']);
                echo htmlspecialchars($initials[0][0] . $initials[1][0]);
                ?>
            </div>
        </div>

        <h2 class="name"><?php echo htmlspecialchars($data['name']); ?></h2>
        <p class="role">&lt; <?php echo htmlspecialchars($data['role']); ?> /&gt;</p>

        <ul class="meta-list">
            <li><span class="prompt">$</span> loc: <?php echo htmlspecialchars($data['location']); ?></li>
            <li><span class="prompt">$</span> status: <span class="blink">ONLINE</span></li>
        </ul>
    </aside>

    <section class="terminal-box about">
        <div class="terminal-content">
            <p><span class="prompt">user@antonin:~$</span> cat description.txt</p>
            <p>
                Bezpečnostní specialista a DevOps inženýr se zaměřením na ochranu systémů,
                penetrační testování a automatizaci infrastruktury.
            </p>
        </div>
    </section>

    <section class="terminal-box skills">
        <div class="terminal-content">
            <ul class="skills-list">
                <?php foreach ($data['skills'] as $skill): ?>
                    <li>
                        <span class="prompt">&gt;</span>
                        <?php echo htmlspecialchars($skill); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <section class="terminal-box interests">
        <div class="terminal-content">
            <?php foreach ($data['interests'] as $interest): ?>
                <p>
                    <span class="prompt">$</span>
                    <?php echo htmlspecialchars($interest); ?>
                </p>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="terminal-box projects">
        <div class="terminal-content">
            <?php foreach ($data['projects'] as $project): ?>
                <div class="project">
                    <p>
                        <span class="prompt">[PROJECT]</span>
                        <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                    </p>
                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                    <p>
                        Link:
                        <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank">
                            <?php echo htmlspecialchars($project['link']); ?>
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<footer class="site-footer">
    <small>
        Session ID: <?php echo $sessionId; ?> | © 2026 <?php echo htmlspecialchars($data['name']); ?>
    </small>
</footer>

</body>
</html>