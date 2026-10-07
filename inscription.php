<?php
    session_start();
    require 'db.php';

    if (empty($_SESSION['user']['idEtudiant'])) {
        header('Location: login.php');
        exit();
    }
    $user = $_SESSION['user'];
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseId = filter_input(INPUT_POST, 'idCours', FILTER_VALIDATE_INT);

        if (!$courseId) {
            $error = 'Veuillez sélectionner un cours valide.';
        } else {
            $db->beginTransaction();
            $courseQuery = $db->prepare("SELECT placesRestantes FROM cours WHERE idCours = ? FOR UPDATE");
            $courseQuery->execute([$courseId]);
            $course = $courseQuery->fetch();

            $duplicateQuery = $db->prepare("SELECT idInscription FROM inscription WHERE idEtudiant = ? AND idCours = ? AND statut <> 'annulé'");
            $duplicateQuery->execute([$user['idEtudiant'], $courseId]);

            if (!$course) {
                $db->rollBack();
                $error = 'Ce cours n’existe plus.';
            } elseif ($duplicateQuery->fetch()) {
                $db->rollBack();
                $error = 'Vous êtes déjà inscrit à ce cours.';
            } elseif ((int) $course['placesRestantes'] < 1) {
                $db->rollBack();
                $error = 'Il ne reste plus de place pour ce cours.';
            } else {
                $insert = $db->prepare("INSERT INTO inscription (idEtudiant, idCours, dateInscription, statut, paiement) VALUES (?, ?, CURDATE(), 'en attente', 'non payé')");
                $insert->execute([$user['idEtudiant'], $courseId]);
                $update = $db->prepare("UPDATE cours SET placesRestantes = placesRestantes - 1 WHERE idCours = ? AND placesRestantes > 0");
                $update->execute([$courseId]);
                $db->commit();
                $success = 'Votre demande d’inscription a été envoyée.';
            }
        }
    }

    $sql = 'SELECT * FROM cours WHERE placesRestantes > 0 AND dateDebut >= CURDATE() ORDER BY dateDebut';
    $req = $db->prepare($sql);
    $req->execute();
    $cours = $req->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à un cours</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"rel="stylesheet">
</head>

<body class="bg-light">
<?php include 'header.php'; ?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h3>Inscription à un cours</h3>
                </div>

                <div class="card-body">

                    <form action="" method="post">

                        <div class="mb-3">
                            <label class="form-label">
                                Choisir un cours
                            </label>

                            <select name="idCours"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Sélectionnez un cours --
                                </option>

                                <?php foreach($cours as $c){ ?>

                                            <option value="<?= (int) $c['idCours'] ?>">
                                                <?= escape($c['langue']) ?>
                                        -
                                                <?= escape($c['niveau']) ?>
                                                (Places : <?= (int) $c['placesRestantes'] ?>)
                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-success">
                                Confirmer l'inscription
                            </button>
                        </div>

                    </form>

                    <!-- Messages -->
                    <?php if($error !== ''){ ?>
                        <div class="alert alert-danger mt-4">
                            <?= escape($error) ?>
                        </div>
                    <?php } ?>

                    <?php if($success !== ''){ ?>
                        <div class="alert alert-success mt-4">
                            <?= escape($success) ?>
                        </div>
                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>