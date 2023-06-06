<?php
    session_start();
    
    
    require('../DBConnect.php');


if (isset($_SESSION['username'])) {
    // Benutzer ist bereits angemeldet, Weiterleitung zur geschützten Seite
    header('Location: admin.php');
    exit;
}

// Anmeldeformular verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Benutzer aus der Datenbank abrufen
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Anmeldung erfolgreich
            $_SESSION['username'] = $user['name'];
            // Zurück zur ursprünglichen Seite weiterleiten
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                // Fallback, wenn die vorherige Seite nicht bekannt ist
                header('Location: admin.php');
            }
            exit;
        } else {
            echo 'Falsches Passwort.';
        }
    } else {
        echo 'Benutzer nicht gefunden.';
    }
}

$conn = NULL;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anmeldung</title>
</head>
<body>
    <h1>Anmeldung</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Anmelden">
    </form>
</body>
</html>
