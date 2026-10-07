<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['user']['idEtudiant'])) {
        header('Location: login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Méthode non autorisée.');
    }
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        http_response_code(400);
        exit('Identifiant de demande invalide.');
    }

    $db->beginTransaction();
    $select = $db->prepare("SELECT idCours FROM inscription WHERE idInscription = ? AND idEtudiant = ? AND statut = 'en attente' FOR UPDATE");
    $select->execute([$id, $_SESSION['user']['idEtudiant']]);
    $enrollment = $select->fetch();

    if (!$enrollment) {
        $db->rollBack();
        http_response_code(404);
        exit('Demande introuvable ou déjà traitée.');
    }

    $delete = $db->prepare("DELETE FROM inscription WHERE idInscription = ?");
    $delete->execute([$id]);
    $update = $db->prepare("UPDATE cours SET placesRestantes = LEAST(placesRestantes + 1, placesTotal) WHERE idCours = ?");
    $update->execute([$enrollment['idCours']]);
    $db->commit();

    header('Location: MesDemande.php');
    exit();
?>
