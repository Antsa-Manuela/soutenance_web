<?php
// backend/api/admin_login.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$email = $_POST['email'] ?? '';
$motDePasse = $_POST['mot_de_passe'] ?? '';

if (!$email || !$motDePasse) {
  echo json_encode(["success" => false, "message" => "Champs manquants"]);
  exit;
}

// Vérifie si l'utilisateur existe
$stmt = $pdo->prepare("
  SELECT u.id, u.nomComplet, u.email, u.motDePasse
  FROM utilisateur u
  JOIN administrateur a ON a.id_admin = u.id
  WHERE u.email = ?
");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || $admin['motDePasse'] !== $motDePasse) {
  echo json_encode(["success" => false, "message" => "Identifiants invalides"]);
  exit;
}

echo json_encode([
  "success" => true,
  "message" => "Connexion réussie",
  "id_admin" => $admin['id'],
  "nom" => $admin['nomComplet']
]);
?>