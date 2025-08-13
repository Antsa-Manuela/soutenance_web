<?php
// backend/api/modifier_hebergement.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
require_once "../config/db.php";

$id = $_POST['id'] ?? null;
$nom = $_POST['nom'] ?? '';
$type = $_POST['type'] ?? '';
$description = $_POST['description'] ?? '';
$prix = $_POST['prix'] ?? 0;
$adultes = $_POST['adultes'] ?? 0;
$enfants = $_POST['enfants'] ?? 0;
$services = $_POST['services'] ?? '';

if (!$id || !$nom || !$type || !$description) {
  echo json_encode(["success" => false, "message" => "Champs manquants"]);
  exit;
}

$stmt = $pdo->prepare("
  UPDATE Hebergement SET
    nom = ?, type = ?, description = ?, prixParNuit = ?,
    capacite_adultes = ?, capacite_enfants = ?, services = ?
  WHERE id_hebergement = ?
");
$stmt->execute([$nom, $type, $description, $prix, $adultes, $enfants, $services, $id]);
echo json_encode(["success" => true]);
?>