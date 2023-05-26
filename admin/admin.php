<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header('Location: loginUser.php');
    exit;
}

// Geschützter Inhalt der Seite
?>


<p><?php echo 'Willkommen, ' . $_SESSION['username'] . '!'; ?><p>

<h1>Admin - ekolfinder</h1>

<a href="addEngine.php">Lok hinzufügen</a>