<?php 
require_once __DIR__ . "/../src/profileRepo.php";

$repo = new profileRepo();
$profile = $repo->getProfile();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="api/updateProfile.php" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="display_name" value="<?= htmlspecialchars($profile['display_name']) ?>">

        <label for="bio">Bio</label>
        <textarea name="bio" id="bio"><?= htmlspecialchars($profile['bio']) ?></textarea>

        <label for="image">Profile Image</label>
        <input type="file" name="avatar">

        <button type="submit">Save</button>

    </form>
    
</body>
</html>