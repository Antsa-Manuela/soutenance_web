<?php
// backend/api/update_reservation_statut.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once "../config/db.php";

$id = $_POST['id'] ?? null;
$statut = $_POST['statut'] ?? null;
$justification = $_POST['justification'] ?? null;

if (!$id || !$statut) {
  echo json_encode(["success" => false, "message" => "ID ou statut manquant"]);
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE reservation SET statut = ?, justification = ? WHERE id_reservation = ?");
  $stmt->execute([$statut, $justification, $id]);

  echo json_encode(["success" => true, "message" => "Statut mis à jour"]);
} catch (PDOException $e) {
  echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
}
