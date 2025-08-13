<?php
// backend/api/supprimer_hebergement.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once "../config/db.php";

$id = $_POST['id'] ?? null;

if (!$id) {
  echo json_encode(["success" => false, "message" => "ID manquant"]);
  exit;
}

try {
  $stmt = $pdo->prepare("DELETE FROM Hebergement WHERE id_hebergement = ?");
  $stmt->execute([$id]);

  echo json_encode(["success" => true, "message" => "Hébergement supprimé"]);
} catch (PDOException $e) {
  echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
}
?>