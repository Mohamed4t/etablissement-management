<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Méthode non autorisée.');
    }
    session_destroy();
    header('Location: login.php');
    exit();
?>