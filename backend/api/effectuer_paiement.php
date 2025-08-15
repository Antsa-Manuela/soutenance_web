<?php
// backend/api/effectuer_paiement
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idReservation = $_POST['id_reservation'] ?? null;
$montant = $_POST['montant'] ?? 0;
$methode = $_POST['methode'] ?? 'Mvola';
$date = date('Y-m-d');

if (!$idReservation || !$montant || !$methode) {
  echo json_encode(["success" => false, "message" => "Données manquantes"]);
  exit;
}

$stmt = $pdo->prepare("
  INSERT INTO paiement (montant, methode, date, id_reservation)
  VALUES (?, ?, ?, ?)
");
$stmt->execute([$montant, $methode, $date, $idReservation]);

echo json_encode(["success" => true, "message" => "Paiement enregistré"]);
?>