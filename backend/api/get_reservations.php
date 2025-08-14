<?php
// backend/api/get_reservations.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idHote = $_GET['id_hote'] ?? null;
if (!$idHote) {
  echo json_encode(["error" => "ID hôte manquant"]);
  exit;
}

try {
  // Récupérer l’hôtel lié à l’hôte
  $stmt = $pdo->prepare("SELECT id_hotel FROM Hotel WHERE id_hote = ?");
  $stmt->execute([$idHote]);
  $hotel = $stmt->fetch();

  if (!$hotel) {
    echo json_encode(["reservations" => []]);
    exit;
  }

  $idHotel = $hotel['id_hotel'];

  // Récupérer les réservations liées à cet hôtel
  $stmt2 = $pdo->prepare("
    SELECT r.id_reservation, r.dateDebut, r.dateFin, r.nbAdultes, r.nbEnfants, r.statut, r.id_client, u.nomComplet AS nomClient
    FROM Reservation r
    JOIN Utilisateur u ON r.id_client = u.id
    WHERE r.id_hotel = ?
    ORDER BY r.dateDebut DESC
    ");

  $stmt2->execute([$idHotel]);
  $reservations = $stmt2->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(["reservations" => $reservations]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
