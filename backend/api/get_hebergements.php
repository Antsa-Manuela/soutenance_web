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
  $stmt = $pdo->prepare("
  SELECT h.*, GROUP_CONCAT(p.url) AS photos
  FROM Hebergement h
  LEFT JOIN photo p ON p.id_hebergement = h.id_hebergement
  WHERE h.id_hotel IN (
    SELECT id_hotel FROM Hotel WHERE id_hote = ?
  )
  GROUP BY h.id_hebergement
  ");
  $stmt->execute([$idHote]);
  $hebergements = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($hebergements as &$h) {
    $h['photos'] = $h['photos'] ? explode(',', $h['photos']) : [];
  }

  echo json_encode(["hebergements" => $hebergements]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>