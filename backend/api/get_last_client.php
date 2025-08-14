<?php
// backend\api\get_last_client.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idHote = $_GET['id_hote'] ?? null;
if (!$idHote) {
  echo json_encode(["error" => "ID hôte manquant"]);
  exit;
}

try {
  // Récupère le dernier client ayant envoyé ou reçu un message avec cet hôte
  $stmt = $pdo->prepare("
    SELECT id_expediteur AS idClient
    FROM message
    WHERE id_destinataire = ? 
    UNION
    SELECT id_destinataire AS idClient
    FROM message
    WHERE id_expediteur = ?
    ORDER BY id_message DESC
    LIMIT 1
  ");
  $stmt->execute([$idHote, $idHote]);
  $client = $stmt->fetch();

  if ($client) {
    echo json_encode(["idClient" => $client['idClient']]);
  } else {
    echo json_encode(["error" => "Aucun client trouvé"]);
  }
} catch (PDOException $e) {
  echo json_encode(["error" => $e->getMessage()]);
}
