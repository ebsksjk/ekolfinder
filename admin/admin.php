<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header('Location: loginUser.php');
    exit;
}

// Geschützter Inhalt der Seite
?>

<html>
    <head>
        <link rel="stylesheet" href="../styles/main.css">
        <link rel="stylesheet" href="../styles/table.css">
        <link rel="stylesheet" href="../styles/engines.css">
    </head>
    <body>
        <p><?php echo 'Willkommen, ' . $_SESSION['username'] . '!'; ?><p>

        <h1 class="title">Admin - ekolfinder</h1>

        <a href="engines/addEngine.php">Lok hinzufügen</a>
        <br>
        <a href="engines/editEngine.php">Lok bearbeiten</a>
        <br>
        <a href="trains/addTrain.php">Zug hinzufügen</a>
        <br>
        <a href="trains/editTrain.php">Zug bearbeiten</a>
    </body>
    
</html>