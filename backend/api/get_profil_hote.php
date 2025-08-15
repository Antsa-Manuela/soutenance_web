<?php
// backend/api/get_profil_hote.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

$idHote = $_GET['id_hote'] ?? null;
if (!$idHote) {
  echo json_encode(["error" => "ID hôte manquant"]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT 
    h.nomEtablissement, h.adresse, h.typeHebergement, h.langue,
    u.email, u.telephone
  FROM hote h
  JOIN utilisateur u ON h.id_hote = u.id
  WHERE h.id_hote = ?
");
$stmt->execute([$idHote]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
