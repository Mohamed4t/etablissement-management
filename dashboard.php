<?php
session_start();
require 'db.php';
if (empty($_SESSION['admin']['idAdmin'])) {
    header('Location: loginAdmin.php');
    exit();
}
$totalEtudiants = $db->query("SELECT COUNT(*) FROM Etudiant")->fetchColumn();
$totalCours = $db->query("SELECT COUNT(*) FROM Cours")->fetchColumn();
$totalInscriptions = $db->query("SELECT COUNT(*) FROM Inscription")->fetchColumn();
$totalPaiements = $db->query("SELECT COUNT(*) FROM Inscription WHERE paiement='payé'")->fetchColumn();
$pendingInscriptions = $db->query("SELECT COUNT(*) FROM Inscription WHERE statut='en attente'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #F5F5F3; display: flex; min-height: 100vh; }

  /* Main content */
  .main { margin-left: 0; flex: 1; padding: 2rem; }
  .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
  .topbar h1 { font-size: 18px; font-weight: 500; }
  .topbar span { font-size: 13px; color: #888; }

  /* Stat cards */
  .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 2rem; }
  .stat-card { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 12px; padding: 1rem 1.1rem; }
  .stat-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; font-size: 17px; }
  .ic-purple { background: #EEEDFE; color: #534AB7; }
  .ic-teal   { background: #E1F5EE; color: #0F6E56; }
  .ic-blue   { background: #E6F1FB; color: #185FA5; }
  .ic-amber  { background: #FAEEDA; color: #854F0B; }
  .stat-label { font-size: 12px; color: #888; margin-bottom: 4px; }
  .stat-value { font-size: 22px; font-weight: 600; color: #111; }

  /* Quick access */
  .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #999; font-weight: 500; margin-bottom: 12px; }
  .quick-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .quick-card { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 12px; text-decoration: none; transition: border-color 0.15s; }
  .quick-card:hover { border-color: #aaa; }
  .quick-card i { font-size: 20px; color: #534AB7; }
  .quick-card p { font-size: 13.5px; font-weight: 500; color: #111; margin: 0; }
  .quick-card span { font-size: 12px; color: #888; }
  @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 700px) { .main { margin-left: 0; padding: 1rem; } .stats-grid, .quick-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<?php include_once 'headerAdmin.php' ?>

<main class="admin-main main">
  <div class="topbar">
    <h1>Tableau de bord</h1>
    <span><?= date('d/m/Y') ?></span>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon ic-purple"><i class="ti ti-school"></i></div>
      <div class="stat-label">Étudiants</div>
      <div class="stat-value"><?= $totalEtudiants ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon ic-teal"><i class="ti ti-book"></i></div>
      <div class="stat-label">Cours</div>
      <div class="stat-value"><?= $totalCours ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon ic-blue"><i class="ti ti-clipboard-list"></i></div>
      <div class="stat-label">Inscriptions</div>
      <div class="stat-value"><?= $totalInscriptions ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon ic-amber"><i class="ti ti-cash"></i></div>
      <div class="stat-label">Paiements traités</div>
      <div class="stat-value"><?= $totalPaiements ?></div>
    </div>
  </div>

  <div class="section-title">À traiter : <?= (int) $pendingInscriptions ?> demande(s) en attente</div>
  <div class="quick-grid">
    <a href="inscriptionAdmin.php" class="quick-card">
      <i class="ti ti-clipboard-list"></i>
      <div><p>Gérer les inscriptions</p><span>Voir les inscriptions et paiements</span></div>
    </a>
    <a href="gestionCours.php" class="quick-card">
      <i class="ti ti-book-2"></i>
      <div><p>Gérer les cours</p><span>Ajouter ou modifier des cours</span></div>
    </a>
    <a href="etudiants.php" class="quick-card">
      <i class="ti ti-users"></i>
      <div><p>Étudiants</p><span>Consulter les comptes inscrits</span></div>
    </a>
    <a href="cousnonvalid.php" class="quick-card">
      <i class="ti ti-check"></i>
      <div><p>Demandes en attente</p><span>Valider les nouvelles demandes</span></div>
    </a>
  </div>
</main>

</body>
</html>