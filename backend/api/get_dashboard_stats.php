<?php
// backend/api/get_dashboard_stats.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "../config/db.php";

// Récupération de l'ID hôte si fourni
$id_hote = $_GET['id_hote'] ?? null;

try {
  // 🔹 Statistiques spécifiques à l'hôte
  $reservations = 0;
  $vues = 0;
  $messages = 0;
  $totalMessages = 0;

  if ($id_hote) {
    // Réservations en attente
    $stmt1 = $pdo->prepare("
      SELECT COUNT(*) 
      FROM reservation 
      WHERE statut = 'en attente' 
      AND id_hotel IN (
        SELECT id_hote FROM hote WHERE id_hote = ?
      )
    ");
    $stmt1->execute([$id_hote]);
    $reservations = $stmt1->fetchColumn() ?: 0;

    // Nombre de vues
    $stmt2 = $pdo->prepare("
      SELECT SUM(nbVues) 
      FROM statistique 
      WHERE id_hotel IN (
        SELECT id_hote FROM hote WHERE id_hote = ?
      )
    ");
    $stmt2->execute([$id_hote]);
    $vues = $stmt2->fetchColumn() ?: 0;

    // Messages non lus
    $stmt3 = $pdo->prepare("
      SELECT COUNT(*) 
      FROM message 
      WHERE id_destinataire = ? AND vu = 0
    ");
    $stmt3->execute([$id_hote]);
    $messages = $stmt3->fetchColumn() ?: 0;

    // Total messages reçus
    $stmt4 = $pdo->prepare("
      SELECT COUNT(*) 
      FROM message 
      WHERE id_destinataire = ?
    ");
    $stmt4->execute([$id_hote]);
    $totalMessages = $stmt4->fetchColumn() ?: 0;
  }

  // 🔹 Statistiques globales : utilisateurs par rôle
  $stmt5 = $pdo->query("
    SELECT role, COUNT(*) as total 
    FROM utilisateur 
    GROUP BY role
  ");
  $roles = $stmt5->fetchAll(PDO::FETCH_ASSOC);

  // 🔹 Statistiques globales : événements (⚠️ colonne 'statut' supprimée ici)
  // Si tu veux des stats sur les événements, assure-toi que la table `evenement` existe
  // et adapte la requête selon ses colonnes réelles.
  $evenements = []; // ← vide pour éviter l'erreur

  // 🔹 Réponse JSON
  echo json_encode([
    "reservations" => $reservations,
    "vues" => $vues,
    "messages" => $messages,
    "totalMessages" => $totalMessages,
    "roles" => $roles,
    "evenements" => $evenements
  ]);
} catch (PDOException $e) {
  echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
