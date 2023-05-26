<?php
ob_start(); // Pufferung starten

require('../DBConnect.php');

session_start();

// Überprüfen, ob der Benutzer angemeldet ist
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