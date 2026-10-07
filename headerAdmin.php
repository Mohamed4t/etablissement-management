<?php
    $currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
  .admin-sidebar { width: 210px; flex-shrink: 0; background: #fff; border-right: 1px solid #e5e5e5; padding: 1.25rem 1rem; display: flex; flex-direction: column; gap: 4px; position: fixed; inset: 0 auto 0 0; height: 100vh; z-index: 10; }
  .admin-sidebar a { text-decoration: none; }
  .admin-sidebar .sidebar-logo { font-size: 15px; font-weight: 500; color: #111; display: flex; align-items: center; gap: 8px; margin-bottom: 1.5rem; padding: 0 6px; }
  .admin-sidebar .sidebar-logo i { font-size: 19px; color: #534AB7; }
  .admin-sidebar .nav-section { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: #bbb; font-weight: 500; padding: 0 10px; margin: 10px 0 4px; }
  .admin-sidebar .nav-item { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 8px; font-size: 13.5px; color: #666; }
  .admin-sidebar .nav-item:hover { background: #f5f5f5; color: #111; }
  .admin-sidebar .nav-item.active { background: #EEEDFE; color: #534AB7; font-weight: 500; }
  .admin-sidebar .nav-item i { font-size: 17px; }
  .admin-sidebar .nav-spacer { flex: 1; }
  .admin-sidebar .nav-danger { color: #c0392b; }
  .admin-main { margin-left: 210px; padding: 2rem; flex: 1; min-width: 0; }
  @media (max-width: 700px) {
    .admin-sidebar { width: 100%; height: auto; position: static; flex-direction: row; flex-wrap: wrap; align-items: center; border-right: 0; border-bottom: 1px solid #e5e5e5; }
    .admin-sidebar .sidebar-logo { width: 100%; margin-bottom: .4rem; }
    .admin-sidebar .nav-section, .admin-sidebar .nav-spacer { display: none; }
    .admin-main { margin-left: 0; padding: 1rem; }
  }
</style>
<nav class="admin-sidebar">
  <a class="sidebar-logo" href="dashboard.php"><i class="ti ti-language"></i> EcoleLangue</a>
  <div class="nav-section">Principal</div>
  <a href="dashboard.php" class="nav-item <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
  <a href="cousnonvalid.php" class="nav-item <?= $currentPage === 'cousnonvalid.php' ? 'active' : '' ?>"><i class="ti ti-check"></i> Validation</a>
  <div class="nav-section">Gestion</div>
  <a href="gestionCours.php" class="nav-item <?= $currentPage === 'gestionCours.php' ? 'active' : '' ?>"><i class="ti ti-book"></i> Cours</a>
  <a href="inscriptionAdmin.php" class="nav-item <?= $currentPage === 'inscriptionAdmin.php' ? 'active' : '' ?>"><i class="ti ti-clipboard-list"></i> Inscriptions</a>
  <a href="etudiants.php" class="nav-item <?= $currentPage === 'etudiants.php' ? 'active' : '' ?>"><i class="ti ti-users"></i> Étudiants</a>
  <div class="nav-spacer"></div>
  <form action="deconnecterAdmin.php" method="post">
    <button type="submit" class="nav-item nav-danger border-0 bg-transparent w-100 text-start"><i class="ti ti-logout"></i> Déconnexion</button>
  </form>
</nav>
