<!-- 
 
Main landing page

-->

<?php
require_once __DIR__ . '/../src/profileRepo.php';
require_once __DIR__ . '/../src/linkRepo.php';
require_once __DIR__ . '/../src/boxRepo.php';
require_once __DIR__ . '/../src/boxView.php';

$boxRepo = new BoxRepository();
$boxes = $boxRepo->getBoxes(true);

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
    <title>Melanie Adams Portfolio</title>
    <link rel="stylesheet" href="styles.css">
    <link href=" https://cdn.jsdelivr.net/npm/gridstack@12.4.2/dist/gridstack.min.css " rel="stylesheet">
</head>

<body data-page="public">
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
    <div class="grid-stack">
        <?php foreach ($boxes as $box):
            renderBox($box, false);
        endforeach; ?>
    </div>
     <script>
        window.IS_ADMIN = false;
    </script>
    <script src=" https://cdn.jsdelivr.net/npm/gridstack@12.4.2/dist/gridstack-all.min.js "></script>
    <script src="script.js"></script>
</body>

</html>