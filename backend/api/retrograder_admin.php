<?php
// backend/api/retrograder_admin.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['id'] ?? null;
if (!$id) {
  echo json_encode(["success" => false, "message" => "ID manquant"]);
  exit;
}

$stmt = $pdo->prepare("UPDATE utilisateur SET role = 'hote' WHERE id = ?");
$stmt->execute([$id]);

$stmtDelete = $pdo->prepare("DELETE FROM administrateur WHERE id_admin = ?");
$stmtDelete->execute([$id]);

echo json_encode(["success" => true, "message" => "Admin rétrogradé en hôte"]);
?>