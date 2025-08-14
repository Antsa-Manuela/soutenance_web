<?php
// backend/api/envoyer_message.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$expediteur = $data['id_expediteur'] ?? null;
$destinataire = $data['id_destinataire'] ?? null;
$contenu = $data['contenu'] ?? null;

if (!$expediteur || !$destinataire || !$contenu) {
  echo json_encode(["error" => "Données manquantes"]);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO message (contenu, date, vu, id_expediteur, id_destinataire) VALUES (?, NOW(), 0, ?, ?)");
  $stmt->execute([$contenu, $expediteur, $destinataire]);
  echo json_encode(["success" => true]);
} catch (PDOException $e) {
  echo json_encode(["error" => $e->getMessage()]);
}
