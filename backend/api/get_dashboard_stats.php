<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$id_hote = $_GET['id_hote'] ?? null;

if (!$id_hote) {
  echo json_encode(["error" => "ID hôte manquant"]);
  exit;
}

try {
  // Réservations en cours
  $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM reservation WHERE statut = 'en attente' AND id_hotel IN (SELECT id_hotel FROM hotel WHERE id_hote = ?)");
  $stmt1->execute([$id_hote]);
  $reservations = $stmt1->fetchColumn();

  // Nombre de vues
  $stmt2 = $pdo->prepare("SELECT SUM(nbVues) FROM statistique WHERE id_hotel IN (SELECT id_hotel FROM hotel WHERE id_hote = ?)");
  $stmt2->execute([$id_hote]);
  $vues = $stmt2->fetchColumn();

  // Nouveaux messages non lus
  $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ? AND vu = 0");
  $stmt3->execute([$id_hote]);
  $messages = $stmt3->fetchColumn();

  // Total messages reçus
  $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM message WHERE id_destinataire = ?");
  $stmt4->execute([$id_hote]);
  $totalMessages = $stmt4->fetchColumn();

  echo json_encode([
    "reservations" => $reservations ?: 0,
    "vues" => $vues ?: 0,
    "messages" => $messages ?: 0,
    "totalMessages" => $totalMessages ?: 0
  ]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
