<?php
// backend/hote_register.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nomEtablissement = $_POST['nomEtablissement'];
  $email = $_POST['email'];
  $telephone = $_POST['telephone'];
  $motDePasse = $_POST['motDePasse'];
  $adresse = $_POST['adresse'];
  $typeHebergement = $_POST['typeHebergement'];
  $justificatif = file_get_contents($_FILES['justificatif']['tmp_name']);

  // Vérifier si l’email existe déjà
  $check = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
  $check->execute([$email]);
  if ($check->rowCount() > 0) {
    echo json_encode(["message" => "Email déjà utilisé."]);
    exit;
  }

  // Insérer dans Utilisateur
  $stmt = $pdo->prepare("INSERT INTO Utilisateur (nomComplet, email, motDePasse) VALUES (?, ?, ?)");
  $stmt->execute([$nomEtablissement, $email, $motDePasse]);
  $idUtilisateur = $pdo->lastInsertId();

  // Insérer dans Hote
  $stmt2 = $pdo->prepare("INSERT INTO Hote (id_hote, nomEtablissement, typeHebergement, adresse, justificatifs) VALUES (?, ?, ?, ?, ?)");
  $stmt2->execute([$idUtilisateur, $nomEtablissement, $typeHebergement, $adresse, $justificatif]);

  echo json_encode(["message" => "Compte hôte créé avec succès !"]);
}
?>
