<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['admin']['idAdmin'])) {
        header('Location: loginAdmin.php');
        exit();
    }

    $sql = "SELECT i.*, c.langue, c.niveau, e.nom, e.email
            FROM inscription i
            JOIN cours c ON i.idCours = c.idCours
            JOIN etudiant e ON i.idEtudiant = e.idEtudiant
            WHERE i.statut = 'en attente'
            ORDER BY i.dateInscription ASC";
    $inscriptions = $db->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validation des demandes — EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #F5F5F3; }
    .table-wrap { overflow-x: auto; }
    main { min-width: 0; }
  </style>
</head>
<body class="d-flex min-vh-100">
<?php include 'headerAdmin.php'; ?>
<main class="admin-main">
  <header class="mb-4">
    <h1 class="h4 mb-1">Demandes en attente</h1>
    <p class="text-secondary mb-0">Validez, annulez ou marquez les inscriptions confirmées comme payées.</p>
  </header>
  <div class="card shadow-sm border-0">
    <div class="table-wrap">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Étudiant</th><th>Cours</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if ($inscriptions): ?>
            <?php foreach ($inscriptions as $item): ?>
              <tr>
                <td><?= escape($item['nom']) ?><br><small class="text-secondary"><?= escape($item['email']) ?></small></td>
                <td><?= escape($item['langue']) ?> — <?= escape($item['niveau']) ?></td>
                <td><?= escape($item['dateInscription']) ?></td>
                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <?php foreach (['valid' => 'Valider', 'annuler' => 'Annuler'] as $action => $label): ?>
                      <form action="actionInscription.php" method="post">
                        <input type="hidden" name="id" value="<?= (int) $item['idInscription'] ?>">
                        <input type="hidden" name="action" value="<?= escape($action) ?>">
                        <button class="btn btn-sm <?= $action === 'valid' ? 'btn-success' : 'btn-outline-danger' ?>" type="submit"><?= escape($label) ?></button>
                      </form>
                    <?php endforeach; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center text-secondary py-5">Aucune demande en attente.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
