<?php
// backend/api/modifier_profil_hote.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
require_once "../config/db.php";

$photoPath = null;
if (isset($_FILES['photoProfil']) && $_FILES['photoProfil']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = '../../uploads/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }
  $filename = uniqid() . '_' . basename($_FILES['photoProfil']['name']);
  $targetPath = $uploadDir . $filename;

  if (move_uploaded_file($_FILES['photoProfil']['tmp_name'], $targetPath)) {
    $photoPath = 'uploads/' . $filename;
  }
}

$idHote = $_POST['id_hote'] ?? null;
$nom = $_POST['nomEtablissement'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$type = $_POST['typeHebergement'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$langue = $_POST['langue'] ?? '';

if (!$idHote || !$nom || !$email) {
  echo json_encode(["success" => false, "message" => "Champs obligatoires manquants"]);
  exit;
}

// Mise à jour dans hote
$stmt1 = $pdo->prepare("
  UPDATE hote SET nomEtablissement = ?, adresse = ?, typeHebergement = ?, langue = ?
  WHERE id_hote = ?
");
$stmt1->execute([$nom, $adresse, $type, $langue, $idHote]);

// Mise à jour dans utilisateur
if ($photoPath) {
  $stmt2 = $pdo->prepare("UPDATE utilisateur SET email = ?, telephone = ?, photoProfil = ? WHERE id = ?");
  $stmt2->execute([$email, $telephone, $photoPath, $idHote]);
} else {
  $stmt2 = $pdo->prepare("UPDATE utilisateur SET email = ?, telephone = ? WHERE id = ?");
  $stmt2->execute([$email, $telephone, $idHote]);
}

echo json_encode(["success" => true]);
?>
