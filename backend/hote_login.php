<?php
// backend/hote_login.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $motDePasse = $_POST['motDePasse'];

  // Vérifie si l'utilisateur existe et est un hôte
  $stmt = $pdo->prepare("
    SELECT h.id_hote, h.nomEtablissement
    FROM Utilisateur u
    JOIN Hote h ON u.id = h.id_hote
    WHERE u.email = ? AND u.motDePasse = ?
  ");
  $stmt->execute([$email, $motDePasse]);

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();
    echo json_encode([
      "success" => true,
      "message" => "Connexion réussie",
      "idHote" => $user['id_hote'], // ← c’est ça qu’on veut stocker
      "nomEtablissement" => $user['nomEtablissement']
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
  }
}
?>
