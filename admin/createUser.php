<?php

    //---------------------------------------------------------------------//
        $usrname 	= "test";
        $passwrd 	= "test";
        $DSN 		= "mysql:host=localhost;dbname=ekolfinder";
    //---------------------------------------------------------------------//

    $conn = null;

    try {
        $conn = new PDO($DSN, $usrname, $passwrd);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die("Die Verbindung zur Datenbank konnte nicht hergestellt werden.");
    }

// Registrierungsformular verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Benutzer in die Datenbank einfügen
    $query = "INSERT INTO users (name, password) VALUES ('$username', '$hashedPassword')";
    $result = $conn->query($query);
    if ($result) {
        echo 'Registrierung erfolgreich.';
    } else {
        echo 'Fehler bei der Registrierung.';
    }
}

$conn = NULL;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrierung</title>
</head>
<body>
    <h1>Registrierung</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Registrieren">
    </form>
</body>
</html>
