<?php
// backend/api/supprimer_utilisateur.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['id'] ?? null;
if (!$id) {
  echo json_encode(["success" => false, "message" => "ID manquant"]);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true, "message" => "Utilisateur supprimé"]);
?>