<?php
// backend/api/ajouter_evenement.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$titre = $_POST['titre'] ?? '';
$dateDebut = $_POST['date_debut'] ?? '';
$dateFin = $_POST['date_fin'] ?? '';
$description = $_POST['description'] ?? '';
$resume = $_POST['resume'] ?? '';
$idAdmin = $_POST['id_admin'] ?? null;

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = '../../uploads/';
  $filename = uniqid('event_', true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
  $targetPath = $uploadDir . $filename;
  if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    $imagePath = 'uploads/' . $filename;
  }
}

if (!$titre || !$dateDebut || !$dateFin || !$idAdmin) {
  echo json_encode(["success" => false, "message" => "Champs obligatoires manquants"]);
  exit;
}

$stmt = $pdo->prepare("
  INSERT INTO evenement (titre, date_debut, date_fin, description, resume, image, id_admin)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$titre, $dateDebut, $dateFin, $description, $resume, $imagePath, $idAdmin]);

echo json_encode(["success" => true, "message" => "Événement ajouté"]);
?>