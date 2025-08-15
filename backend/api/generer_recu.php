<?php
// backend/api/generer_recu.php
require_once "../config/db.php";
require_once "../vendor/autoload.php"; // si tu utilises mPDF

$idPaiement = $_GET['id_paiement'] ?? null;

$stmt = $pdo->prepare("
  SELECT p.*, r.nom_client, r.date_debut, r.date_fin, h.nom_hebergement
  FROM paiement p
  JOIN reservation r ON p.id_reservation = r.id_reservation
  JOIN hebergement h ON r.id_hebergement = h.id_hebergement
  WHERE p.id_paiement = ?
");
$stmt->execute([$idPaiement]);
$paiement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paiement) {
  die("Paiement introuvable");
}

$html = "
  <h1>Reçu de paiement</h1>
  <p>Nom du client : {$paiement['nom_client']}</p>
  <p>Hébergement : {$paiement['nom_hebergement']}</p>
  <p>Montant : {$paiement['montant']} Ar</p>
  <p>Méthode : {$paiement['methode']}</p>
  <p>Date : {$paiement['date']}</p>
";

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$filename = "recu_{$idPaiement}.pdf";
$mpdf->Output("../pdf/$filename", \Mpdf\Output\Destination::FILE);

echo json_encode(["url" => "http://localhost/soutenance_web/pdf/$filename"]);
