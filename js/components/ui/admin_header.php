<?php
    session_start();
    $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : '';
    if($isAdmin != true) {
        header('Location: ../');
        exit();
    }

    header('Content-Type: text/html; charset=utf-8');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
?>