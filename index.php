<?php
$jsonData = file_get_contents('profile.json');
$data = json_decode($jsonData, true);

if (!$data) {
    die('Chyba při načítání dat.');
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