<?php
// backend/api/get_utilisateurs.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$role = $_GET['role'] ?? null;

if ($role) {
  $stmt = $pdo->prepare("SELECT id, nomComplet, email, photoProfil, role FROM utilisateur WHERE role = ? ORDER BY nomComplet ASC");
  $stmt->execute([$role]);
} else {
  $stmt = $pdo->query("SELECT id, nomComplet, email, photoProfil, role FROM utilisateur ORDER BY nomComplet ASC");
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["utilisateurs" => $data]);
?>