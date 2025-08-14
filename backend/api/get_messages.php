<?php
// backend/api/get_messages.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$id1 = $_GET['id1'] ?? null; // hôte
$id2 = $_GET['id2'] ?? null; // client

if (!$id1 || !$id2) {
  echo json_encode(["error" => "Paramètres manquants"]);
  exit;
}

try {
  // Marquer les messages envoyés par le client à l'hôte comme lus
  $stmtVu = $pdo->prepare("
    UPDATE message 
    SET vu = 1 
    WHERE id_destinataire = ? AND id_expediteur = ?
  ");
  $stmtVu->execute([$id1, $id2]); // id1 = hôte, id2 = client

  // Récupérer tous les messages entre hôte et client
  $stmt = $pdo->prepare("
    SELECT * FROM message 
    WHERE (id_expediteur = ? AND id_destinataire = ?) 
       OR (id_expediteur = ? AND id_destinataire = ?)
    ORDER BY date ASC
  ");
  $stmt->execute([$id1, $id2, $id2, $id1]);

  $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($messages);
} catch (PDOException $e) {
  echo json_encode(["error" => $e->getMessage()]);
}
?>