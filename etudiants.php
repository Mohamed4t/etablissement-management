<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['admin']['idAdmin'])) {
        header('Location: loginAdmin.php');
        exit();
    }

    $search = trim($_GET['q'] ?? '');
    if ($search !== '') {
        $query = $db->prepare("SELECT idEtudiant, cin, nom, email, tel, login FROM etudiant WHERE nom LIKE ? OR email LIKE ? OR login LIKE ? OR cin LIKE ? ORDER BY nom");
        $term = '%' . $search . '%';
        $query->execute([$term, $term, $term, $term]);
    } else {
        $query = $db->query("SELECT idEtudiant, cin, nom, email, tel, login FROM etudiant ORDER BY nom");
    }
    $students = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Étudiants — EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body { background: #F5F5F3; } .table-wrap { overflow-x: auto; }</style>
</head>
<body class="d-flex min-vh-100">
<?php include 'headerAdmin.php'; ?>
<main class="admin-main">
  <header class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
      <h1 class="h4 mb-1">Étudiants</h1>
      <p class="text-secondary mb-0"><?= count($students) ?> résultat(s)</p>
    </div>
    <form method="get" class="d-flex gap-2">
      <input class="form-control" name="q" type="search" placeholder="Nom, email, login ou CIN" value="<?= escape($search) ?>">
      <button class="btn btn-primary" type="submit">Rechercher</button>
    </form>
  </header>
  <div class="card shadow-sm border-0">
    <div class="table-wrap">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Nom</th><th>CIN</th><th>Email</th><th>Téléphone</th><th>Login</th></tr></thead>
        <tbody>
          <?php if ($students): ?>
            <?php foreach ($students as $student): ?>
              <tr>
                <td><?= escape($student['nom']) ?></td>
                <td><?= escape($student['cin']) ?></td>
                <td><?= escape($student['email']) ?></td>
                <td><?= escape($student['tel']) ?></td>
                <td><?= escape($student['login']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-secondary py-5">Aucun étudiant trouvé.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
