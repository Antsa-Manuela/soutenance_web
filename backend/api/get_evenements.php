<?php
// backend/api/get_evenements.php
require_once "../config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$stmt = $pdo->query("SELECT * FROM evenement ORDER BY date_debut DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["evenements" => $data]);
?>