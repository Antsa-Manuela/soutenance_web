<?php
// backend/api/ajouter_hebergement.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once "../config/db.php";

$idHote = $_POST['idHote'] ?? null;
$nom = $_POST['nom'] ?? '';
$type = $_POST['type'] ?? '';
$description = $_POST['description'] ?? '';
$prix = $_POST['prix'] ?? 0;
$adultes = $_POST['adultes'] ?? 0;
$enfants = $_POST['enfants'] ?? 0;
$services = $_POST['services'] ?? '';
$dispo_debut = $_POST['dispo_debut'] ?? null;
$dispo_fin = $_POST['dispo_fin'] ?? null;

if (!$idHote || !$nom || !$type || !$description) {
  echo json_encode(["success" => false, "message" => "Champs obligatoires manquants."]);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id_hotel FROM Hotel WHERE id_hote = ?");
  $stmt->execute([$idHote]);
  $hotel = $stmt->fetch();

  if (!$hotel) {
    echo json_encode(["success" => false, "message" => "Aucun hôtel associé à cet hôte."]);
    exit;
  }

  $idHotel = $hotel['id_hotel'];

  // Gestion des photos
  $photoPaths = [];
  foreach ($_FILES as $file) {
    $filename = basename($file['name']);
    $target = "../../uploads/" . $filename;
    $publicPath = "http://localhost/soutenance_web/uploads/" . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target)) {
      $photoPaths[] = $publicPath;
    }    
  }

  $insert = $pdo->prepare("
    INSERT INTO Hebergement (
      id_hotel, nom, type, description, prixParNuit,
      capacite_adultes, capacite_enfants, services,
      photos, dispo_debut, dispo_fin
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $insert->execute([
    $idHotel,
    $nom,
    $type,
    $description,
    $prix,
    $adultes,
    $enfants,
    $services,
    implode(',', $photoPaths),
    $dispo_debut,
    $dispo_fin
  ]);

  $lastHebergementId = $pdo->lastInsertId();

  foreach ($photoPaths as $path) {
    $stmtPhoto = $pdo->prepare("INSERT INTO photo (url, id_hotel, id_hebergement) VALUES (?, ?, ?)");
    $stmtPhoto->execute([$path, $idHotel, $lastHebergementId]);
  }  
  
  echo json_encode(["success" => true, "message" => "Hébergement ajouté avec succès."]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
}
