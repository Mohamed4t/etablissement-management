<?php
    session_start();
    require_once 'db.php';
    if (empty($_SESSION['admin']['idAdmin'])) {
        header('Location: loginAdmin.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Méthode non autorisée.');
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $action = post_string('action');
    if (!$id || !in_array($action, ['valid', 'annuler', 'pay'], true)) {
        http_response_code(400);
        exit('Action ou identifiant invalide.');
    }

    $db->beginTransaction();
    $select = $db->prepare("SELECT idCours, statut FROM inscription WHERE idInscription = ? FOR UPDATE");
    $select->execute([$id]);
    $enrollment = $select->fetch();

    if (!$enrollment) {
        $db->rollBack();
        http_response_code(404);
        exit('Inscription introuvable.');
    }

    if ($action === 'valid' && $enrollment['statut'] === 'en attente') {
        $update = $db->prepare("UPDATE inscription SET statut = 'confirmé' WHERE idInscription = ?");
        $update->execute([$id]);
    } elseif ($action === 'annuler' && $enrollment['statut'] !== 'annulé') {
        $update = $db->prepare("UPDATE inscription SET statut = 'annulé' WHERE idInscription = ?");
        $update->execute([$id]);
        $releaseSeat = $db->prepare("UPDATE cours SET placesRestantes = LEAST(placesRestantes + 1, placesTotal) WHERE idCours = ?");
        $releaseSeat->execute([$enrollment['idCours']]);
    } elseif ($action === 'pay' && $enrollment['statut'] === 'confirmé') {
        $update = $db->prepare("UPDATE inscription SET paiement = 'payé' WHERE idInscription = ?");
        $update->execute([$id]);
    } elseif ($action !== 'annuler' || $enrollment['statut'] === 'annulé') {
        $db->rollBack();
        http_response_code(409);
        exit('Cette action n’est pas disponible pour le statut actuel.');
    }

    $db->commit();
    header('Location: cousnonvalid.php');
    exit();
?>
