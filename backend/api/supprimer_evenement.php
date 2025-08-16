<?php
// backend/api/supprimer_evenement.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['id_event'] ?? null;
if (!$id) {
  echo json_encode(["success" => false, "message" => "ID manquant"]);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM evenement WHERE id_event = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true, "message" => "Événement supprimé"]);
?>