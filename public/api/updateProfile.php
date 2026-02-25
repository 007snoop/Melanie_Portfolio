<?php 
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/profileRepo.php';

$repo = new profileRepo();

$display_name = $_POST['display_name'];
$bio = $_POST['bio'];

$ImgP = null;

// add current img to replace old img
$currProfile = $repo->getProfile();
$currImg = $currProfile['a_url'] ?? null;
$absoluteOldPath = __DIR__.'/../'.$currImg;

if (!empty($_FILES['avatar']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';

    // mkdir to create target
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    } // 0777 = file permissions
    
    $fileName = time() . "_" . basename($_FILES['avatar']['name']);
    $targetFile = $uploadDir . $fileName;

   if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {

        // delete old img if it exists
        if ($currImg && file_exists($absoluteOldPath)) {
            unlink($absoluteOldPath);
        }

        $ImgP = 'uploads/' . $fileName;
        // this is in <img> tag
   };
}

$repo->updateProfile($display_name, $bio, $ImgP);

/* var_dump($_POST);
var_dump($_FILES);
exit; */

header('Location: ../index.php');
exit;