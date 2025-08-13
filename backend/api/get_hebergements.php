<?php
// backend/api/get_hebergements.php
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
    echo json_encode(["hebergements" => []]);
    exit;
  }

  $idHotel = $hotel['id_hotel'];

  // Récupérer les hébergements
  $stmt2 = $pdo->prepare("SELECT * FROM Hebergement WHERE id_hotel = ?");
  $stmt2->execute([$idHotel]);
  $hebergements = $stmt2->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(["hebergements" => $hebergements]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>