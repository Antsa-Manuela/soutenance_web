<?php
// backend/api/get_statistiques.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idHotel = $_GET['id_hotel'] ?? null;
if (!$idHotel) {
  echo json_encode(["error" => "ID hôtel manquant"]);
  exit;
}

try {
  // Nombre de vues
  $stmt1 = $pdo->prepare("SELECT nbVues FROM Statistique WHERE id_hotel = ?");
  $stmt1->execute([$idHotel]);
  $vues = $stmt1->fetchColumn() ?: 0;

  // Réservations confirmées
  $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM Reservation WHERE statut = 'confirmé' AND id_hotel = ?");
  $stmt2->execute([$idHotel]);
  $confirmées = $stmt2->fetchColumn();

  // Réservations annulées
  $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM Reservation WHERE statut = 'annulé' AND id_hotel = ?");
  $stmt3->execute([$idHotel]);
  $annulées = $stmt3->fetchColumn();

  // Taux d’occupation (exemple simplifié)
  $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM Reservation WHERE id_hotel = ?");
  $stmt4->execute([$idHotel]);
  $total = $stmt4->fetchColumn();
  $occupation = $total > 0 ? round(($confirmées / $total) * 100) : 0;

  echo json_encode([
    "vues" => $vues,
    "confirmées" => $confirmées,
    "annulées" => $annulées,
    "occupation" => $occupation
  ]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
