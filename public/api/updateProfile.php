<?php 
require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/profileRepo.php';

$repo = new profileRepo();

$display_name = $_POST['display_name'];
$bio = $_POST['bio'];

$ImgP = null;

if (!empty($_FILES['avatar']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    $fileName = time() . "_" . basename($_FILES['avatar']['name']);
    $targetFile = $uploadDir . $fileName;

   if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
        $ImgP = '/../uploads/' . $fileName;
        // this is in <img> tag
   };
}

$repo->updateProfile($display_name, $bio, $ImgP);

var_dump($_POST);
var_dump($_FILES);
exit;

header('Location: ../index.php');
exit;