<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['admin']['idAdmin'])) {
        header('Location: loginAdmin.php');
        exit();
    }

    $error = '';
    $message = '';
    $editing = null;
    $dateIsValid = static function (string $date): bool {
        $parsed = DateTime::createFromFormat('!Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    };

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = post_string('action');
        $courseId = filter_input(INPUT_POST, 'idCours', FILTER_VALIDATE_INT);

        if ($action === 'delete' && $courseId) {
            $db->beginTransaction();
            $lock = $db->prepare("SELECT idCours FROM cours WHERE idCours = ? FOR UPDATE");
            $lock->execute([$courseId]);
            $lockedCourse = $lock->fetch();
            $check = $db->prepare("SELECT COUNT(*) FROM inscription WHERE idCours = ?");
            $check->execute([$courseId]);
            if (!$lockedCourse) {
                $db->rollBack();
                $error = 'Cours introuvable.';
            } elseif ((int) $check->fetchColumn() > 0) {
                $db->rollBack();
                $error = 'Ce cours ne peut pas être supprimé, car il possède déjà des inscriptions.';
            } else {
                $delete = $db->prepare("DELETE FROM cours WHERE idCours = ?");
                $delete->execute([$courseId]);
                $db->commit();
                $message = 'Le cours a été supprimé.';
            }
        } elseif ($action === 'save') {
            $langue = trim(post_string('langue'));
            $niveau = trim(post_string('niveau'));
            $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_INT);
            $dateDebut = post_string('dateDebut');
            $dateFin = post_string('dateFin');
            $placesTotal = filter_input(INPUT_POST, 'placesTotal', FILTER_VALIDATE_INT);

            if ($langue === '' || $niveau === '' || $prix === false || $prix === null || $prix < 0
                || !$dateIsValid($dateDebut) || !$dateIsValid($dateFin)
                || $dateFin < $dateDebut || !$placesTotal || $placesTotal < 1) {
                $error = 'Vérifiez la langue, le niveau, le prix, les dates et le nombre de places.';
            } elseif ($courseId) {
                $db->beginTransaction();
                $currentQuery = $db->prepare("SELECT placesTotal, placesRestantes FROM cours WHERE idCours = ? FOR UPDATE");
                $currentQuery->execute([$courseId]);
                $current = $currentQuery->fetch();
                if (!$current) {
                    $db->rollBack();
                    $error = 'Cours introuvable.';
                } else {
                    $usedSeats = max(0, (int) $current['placesTotal'] - (int) $current['placesRestantes']);
                    if ($placesTotal < $usedSeats) {
                        $db->rollBack();
                        $error = 'Le nombre total de places ne peut pas être inférieur aux places déjà réservées.';
                    } else {
                        $remaining = $placesTotal - $usedSeats;
                        $update = $db->prepare("UPDATE cours SET langue = ?, niveau = ?, prix = ?, dateDebut = ?, dateFin = ?, placesTotal = ?, placesRestantes = ? WHERE idCours = ?");
                        $update->execute([$langue, $niveau, $prix, $dateDebut, $dateFin, $placesTotal, $remaining, $courseId]);
                        $db->commit();
                        $message = 'Le cours a été modifié.';
                    }
                }
            } else {
                $insert = $db->prepare("INSERT INTO cours (langue, niveau, prix, dateDebut, dateFin, placesTotal, placesRestantes) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert->execute([$langue, $niveau, $prix, $dateDebut, $dateFin, $placesTotal, $placesTotal]);
                $message = 'Le cours a été ajouté.';
            }
        } else {
            http_response_code(400);
            exit('Action invalide.');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_GET['edit'])) {
        $editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
        if ($editId) {
            $query = $db->prepare("SELECT * FROM cours WHERE idCours = ?");
            $query->execute([$editId]);
            $editing = $query->fetch() ?: null;
        }
    }

    $courses = $db->query("SELECT * FROM cours ORDER BY dateDebut DESC, idCours DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des cours — EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body { background: #F5F5F3; } .table-wrap { overflow-x: auto; }</style>
</head>
<body class="d-flex min-vh-100">
<?php include 'headerAdmin.php'; ?>
<main class="admin-main">
  <div class="row g-4">
    <section class="col-12 col-xl-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h1 class="h5 mb-3"><?= $editing ? 'Modifier un cours' : 'Ajouter un cours' ?></h1>
          <?php if ($error !== ''): ?><div class="alert alert-danger"><?= escape($error) ?></div><?php endif; ?>
          <?php if ($message !== ''): ?><div class="alert alert-success"><?= escape($message) ?></div><?php endif; ?>
          <form method="post">
            <input type="hidden" name="action" value="save">
            <?php if ($editing): ?><input type="hidden" name="idCours" value="<?= (int) $editing['idCours'] ?>"><?php endif; ?>
            <div class="mb-3">
              <label class="form-label" for="langue">Langue</label>
              <input class="form-control" id="langue" name="langue" required value="<?= escape($editing['langue'] ?? '') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label" for="niveau">Niveau</label>
              <input class="form-control" id="niveau" name="niveau" required value="<?= escape($editing['niveau'] ?? '') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label" for="prix">Prix (DH)</label>
              <input class="form-control" id="prix" name="prix" type="number" min="0" required value="<?= escape((string) ($editing['prix'] ?? '')) ?>">
            </div>
            <div class="row">
              <div class="mb-3 col-6">
                <label class="form-label" for="dateDebut">Date de début</label>
                <input class="form-control" id="dateDebut" name="dateDebut" type="date" required value="<?= escape($editing['dateDebut'] ?? '') ?>">
              </div>
              <div class="mb-3 col-6">
                <label class="form-label" for="dateFin">Date de fin</label>
                <input class="form-control" id="dateFin" name="dateFin" type="date" required value="<?= escape($editing['dateFin'] ?? '') ?>">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="placesTotal">Nombre de places</label>
              <input class="form-control" id="placesTotal" name="placesTotal" type="number" min="1" required value="<?= escape((string) ($editing['placesTotal'] ?? '')) ?>">
            </div>
            <button class="btn btn-primary" type="submit"><?= $editing ? 'Enregistrer' : 'Ajouter le cours' ?></button>
            <?php if ($editing): ?><a class="btn btn-outline-secondary" href="gestionCours.php">Annuler</a><?php endif; ?>
          </form>
        </div>
      </div>
    </section>
    <section class="col-12 col-xl-8">
      <h2 class="h5 mb-3">Cours enregistrés</h2>
      <div class="card shadow-sm border-0">
        <div class="table-wrap">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Langue / niveau</th><th>Dates</th><th>Prix</th><th>Places</th><th>Actions</th></tr></thead>
            <tbody>
              <?php if ($courses): ?>
                <?php foreach ($courses as $course): ?>
                  <tr>
                    <td><?= escape($course['langue']) ?><br><small class="text-secondary"><?= escape($course['niveau']) ?></small></td>
                    <td><?= escape($course['dateDebut']) ?><br><small class="text-secondary"><?= escape($course['dateFin']) ?></small></td>
                    <td><?= (int) $course['prix'] ?> DH</td>
                    <td><?= (int) $course['placesRestantes'] ?>/<?= (int) $course['placesTotal'] ?></td>
                    <td>
                      <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="gestionCours.php?edit=<?= (int) $course['idCours'] ?>">Modifier</a>
                        <form method="post" onsubmit="return confirm('Supprimer ce cours ?')">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="idCours" value="<?= (int) $course['idCours'] ?>">
                          <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center text-secondary py-5">Aucun cours enregistré.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</main>
</body>
</html>
