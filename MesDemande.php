<?php
    session_start();
    include_once 'db.php';
    if (empty($_SESSION['user']['idEtudiant'])) {
        header('Location: login.php');
        exit();
    }

    $idEtudiant = $_SESSION['user']['idEtudiant'];
    $sql = "SELECT i.*, c.langue, c.niveau 
    FROM inscription i 
    JOIN Cours c ON i.idCours = c.idCours 
    WHERE i.idEtudiant = ? 
    ORDER BY i.dateInscription DESC";
    $req = $db->prepare($sql);
    $req->execute([$idEtudiant]);
    $inscription = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">
<?php include 'header.php'; ?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white text-center">
            <h3>Mes Demandes d'Inscription</h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="table-primary">
                        <tr>
                            <th>Cours</th>
                            <th>Date de la demande</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if(count($inscription) > 0): ?>

                        <?php foreach($inscription as $i): ?>

                            <tr>

                                <td><?= escape($i['langue']) ?> (<?= escape($i['niveau']) ?>)</td>

                                <td><?= escape($i['dateInscription']) ?></td>

                                <td>
                                    <?php if($i['statut'] === 'en attente'): ?>
                                        <span class="badge bg-warning text-dark">
                                            En attente
                                        </span>

                                    <?php elseif($i['statut'] === 'confirmé'): ?>
                                        <span class="badge bg-success">
                                            Confirmée
                                        </span>

                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            Annulée
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td><?= escape($i['paiement']) ?></td>

                                <td>
                                    <?php if($i['statut'] === 'en attente'): ?>
                                        <form action="delete.php" method="post" onsubmit="return confirm('Annuler cette demande ?')">
                                            <input type="hidden" name="id" value="<?= (int) $i['idInscription'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                                        </form>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Aucune demande trouvée.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>