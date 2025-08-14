<?php
// backend/api/get_clients_messagerie.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idHote = $_GET['idHote'] ?? null;

if (!$idHote) {
  echo json_encode(["error" => "ID hôte manquant"]);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT DISTINCT u.id AS id_client, u.nomComplet AS nom
    FROM message m
    JOIN utilisateur u ON u.id = m.id_expediteur
    WHERE m.id_destinataire = ?
    UNION
    SELECT DISTINCT u.id AS id_client, u.nomComplet AS nom
    FROM message m
    JOIN utilisateur u ON u.id = m.id_destinataire
    WHERE m.id_expediteur = ?
  ");
  $stmt->execute([$idHote, $idHote]);
  $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($clients);
} catch (PDOException $e) {
  echo json_encode(["error" => $e->getMessage()]);
}
