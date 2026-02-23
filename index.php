<?php
$jsonData = file_get_contents('profile.json');

if ($jsonData === false) {
    die('Soubor profile.json nebyl nalezen.');
}

$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Chyba v JSON: ' . json_last_error_msg());
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1><?php echo htmlspecialchars($data['name']); ?></h1>

    <h2>Dovednosti</h2>
    <ul>
        <?php foreach ($data['skills'] as $skill): ?>
            <li><?php echo htmlspecialchars($skill); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>IT Zájmy</h2>
    <ul>
        <?php foreach ($data['interests'] as $interest): ?>
            <li><?php echo htmlspecialchars($interest); ?></li>
        <?php endforeach; ?>
    </ul>

</div>

</body>
</html>