<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['admin']['idAdmin'])) {
        header('Location: loginAdmin.php');
        exit();
    }

    $sql = "SELECT i.*, e.nom, e.email, c.langue, c.niveau
            FROM inscription i
            JOIN etudiant e ON i.idEtudiant = e.idEtudiant
            JOIN cours c ON i.idCours = c.idCours
            ORDER BY i.dateInscription DESC, i.idInscription DESC";
    $inscriptions = $db->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscriptions — EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body { background: #F5F5F3; } .table-wrap { overflow-x: auto; }</style>
</head>
<body class="d-flex min-vh-100">
<?php include 'headerAdmin.php'; ?>
<main class="admin-main">
  <header class="mb-4">
    <h1 class="h4 mb-1">Toutes les inscriptions</h1>
    <p class="text-secondary mb-0">Suivi des demandes, des validations et des paiements.</p>
  </header>
  <div class="card shadow-sm border-0">
    <div class="table-wrap">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Étudiant</th><th>Cours</th><th>Date</th><th>Statut</th><th>Paiement</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if ($inscriptions): ?>
            <?php foreach ($inscriptions as $item): ?>
              <tr>
                <td><?= escape($item['nom']) ?><br><small class="text-secondary"><?= escape($item['email']) ?></small></td>
                <td><?= escape($item['langue']) ?> — <?= escape($item['niveau']) ?></td>
                <td><?= escape($item['dateInscription']) ?></td>
                <td><?= escape($item['statut']) ?></td>
                <td><?= escape($item['paiement']) ?></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <?php if ($item['statut'] === 'en attente'): ?>
                      <form action="actionInscription.php" method="post">
                        <input type="hidden" name="id" value="<?= (int) $item['idInscription'] ?>">
                        <input type="hidden" name="action" value="valid">
                        <button class="btn btn-sm btn-success" type="submit">Valider</button>
                      </form>
                    <?php elseif ($item['statut'] === 'confirmé' && $item['paiement'] !== 'payé'): ?>
                      <form action="actionInscription.php" method="post">
                        <input type="hidden" name="id" value="<?= (int) $item['idInscription'] ?>">
                        <input type="hidden" name="action" value="pay">
                        <button class="btn btn-sm btn-outline-success" type="submit">Marquer payé</button>
                      </form>
                    <?php endif; ?>
                    <?php if ($item['statut'] !== 'annulé'): ?>
                      <form action="actionInscription.php" method="post">
                        <input type="hidden" name="id" value="<?= (int) $item['idInscription'] ?>">
                        <input type="hidden" name="action" value="annuler">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Annuler</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-secondary py-5">Aucune inscription enregistrée.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
