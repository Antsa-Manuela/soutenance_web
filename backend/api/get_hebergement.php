<?php
// backend/api/get_hebergement.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$id = $_GET['id'] ?? null;
if (!$id) {
  echo json_encode(["error" => "ID manquant"]);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM Hebergement WHERE id_hebergement = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
?>