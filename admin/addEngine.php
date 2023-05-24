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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $targetDir = '../data/images/';
        $targetFile = $targetDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $response = array(
                'success' => true,
                'message' => 'Bild erfolgreich hochgeladen.'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Fehler beim Hochladen des Bildes.'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Kein Bild ausgewählt.'
        );
    }

    ob_end_clean(); // Puffer leeren
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Beende das Skript hier, um das Frontend nicht auszuführen
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lok hinzufügen</title>
</head>
<body>
    <h1>Lok hinzufügen</h1>
    <form>

    <p><?php echo 'Willkommen, ' . $_SESSION['username'] . '!'; ?><p>
    
    <input type="file" id="fileInput">
    <button onclick="uploadImage()">Bild hochladen</button>
    </form>

    <script>
        function uploadImage() {
            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];
            var formData = new FormData();

            formData.append('image', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log('Bild erfolgreich hochgeladen.');
                    } else {
                        console.log('Fehler beim Hochladen des Bildes: ' + response.message);
                    }
                } else {
                    console.log('Fehler beim Hochladen des Bildes.');
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>
