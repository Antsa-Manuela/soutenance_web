<?php
// backend/api/promouvoir_admin.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['id'] ?? null;
if (!$id) {
  echo json_encode(["success" => false, "message" => "ID manquant"]);
  exit;
}

$stmt = $pdo->prepare("UPDATE utilisateur SET role = 'admin' WHERE id = ?");
$stmt->execute([$id]);

$stmtCheck = $pdo->prepare("SELECT * FROM administrateur WHERE id_admin = ?");
$stmtCheck->execute([$id]);
if ($stmtCheck->rowCount() === 0) {
  $stmtInsert = $pdo->prepare("INSERT INTO administrateur (id_admin) VALUES (?)");
  $stmtInsert->execute([$id]);
}

echo json_encode(["success" => true, "message" => "Utilisateur promu en administrateur"]);
?>