<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['user']['idEtudiant'])) {
        header('Location: login.php');
        exit();
    }

    $id = (int) $_SESSION['user']['idEtudiant'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim(post_string('nom'));
        $email = trim(post_string('email'));
        $tel = trim(post_string('tel'));

        if ($nom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $tel === '') {
            http_response_code(422);
            exit('Veuillez saisir un nom, une adresse email valide et un téléphone.');
        }

        $update = $db->prepare("UPDATE etudiant SET nom = ?, email = ?, tel = ? WHERE idEtudiant = ?");
        $update->execute([$nom, $email, $tel, $id]);
        $_SESSION['user']['nom'] = $nom;
        header('Location: profile.php');
        exit();
    }

    $query = $db->prepare("SELECT * FROM etudiant WHERE idEtudiant = ?");
    $query->execute([$id]);
    $user = $query->fetch();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h3>Mon Profil</h3>
                </div>

                <div class="card-body">

                    <h4 class="mb-4">
                        Bienvenue <?= escape($user['nom']) ?>
                    </h4>

                    <p class="fs-5">
                        <strong>CIN :</strong>
                        <?= escape($user['cin']) ?>
                    </p>

                    <p class="fs-5">
                        <strong>Email :</strong>
                        <?= escape($user['email']) ?>
                    </p>

                    <p class="fs-5">
                        <strong>Téléphone :</strong>
                        <?= escape($user['tel']) ?>
                    </p>

                    <button
                        class="btn btn-warning mt-3"
                        onclick="document.getElementById('formEdit').style.display='block'">
                        Modifier mes informations
                    </button>

                    <div id="formEdit" class="mt-4" style="display:none;">

                        <h5 class="mb-3">Modifier le profil</h5>

                        <form action="" method="post">

                            <div class="mb-3">
                                <label class="form-label">
                                    Nom
                                </label>

                                <input type="text"
                                       name="nom"
                                       class="form-control"
                                       value="<?= escape($user['nom']) ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control" value="<?= escape($user['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Téléphone
                                </label>

                                <input type="text" name="tel" class="form-control" value="<?= escape($user['tel']) ?>" required>
                            </div>

                            <button type="submit"
                                    class="btn btn-success">
                                Enregistrer
                            </button>

                            <button type="button"
                                    class="btn btn-secondary"
                                    onclick="document.getElementById('formEdit').style.display='none'">
                                Annuler
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>