<!-- 
 
Main landing page

-->

<?php
require_once __DIR__ . '/../src/profileRepo.php';
require_once __DIR__ . '/../src/linkRepo.php';
require_once __DIR__ . '/../src/boxRepo.php';

$boxRepo = new BoxRepository();
$boxes = $boxRepo->getBoxes();

$profileRepo = new profileRepo();
$profile = $profileRepo->getProfile();

$linkRepo = new linkRepo();
$links = $linkRepo->getVisibleLinks();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="profile">
        <h1>
            <?= htmlspecialchars($profile['display_name']) ?>
        </h1>
        <!-- add after -->
        <?php if (!empty($profile['bio'])): ?>
            <p class="bio">
                <?= nl2br(htmlspecialchars($profile['bio'])) ?>
            </p>
        <?php endif; ?>
        <ul>
            <?php foreach ($links as $link): ?>
                <li>
                    <a href="<?= htmlspecialchars($link['l_url']) ?>" target="_blank" rel="noopener">
                        <?= htmlspecialchars($link['title']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="bento-container">
        <?php foreach ($boxes as $box): ?>
            <div class="bento-box">
                <h3>
                    <?= htmlspecialchars($box['title']) ?>
                </h3>
                <div class="bento-content">
                    <?= $box['content'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>