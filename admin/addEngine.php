<?php
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header('Location: loginUser.php');
    exit;
}

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_FILES['image']['error'] === 0) {
        //require("../DBConnect.php");

        $uploadFolder = "../data/images/";

        $uploadPath = $uploadFolder . trim($_POST['class']) . '.png';

        $exploder = explode(' ', trim($_POST['class']));

        $br = $exploder[0];
        $nr = $exploder[1];

        try {
            imagepng(imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name'])), $uploadPath);
        } catch(Exception $e) {
            die("Fehler beim Hochladen des Bildes! " . $e->getMessage());
        }
        

        $message = "<div class='successBox'><p>Erfolgreich hochgeladen!</p></div>";

        try {
            require("../DBConnect.php");

            $sql = "INSERT INTO Engines (Baureihe, Ordnungsnummer, Name, Owner) VALUES (?,?,?,?);";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$br, $nr, trim($_POST['name']), trim($_POST['owner'])]);    
        } catch(Exception $e) {
            die("Fehler beim Hochladen der Daten! " . $e->getMessage());
        }

        
    } else {
        $message = "Fehler beim Upload";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Lok hinzufügen</title>
        <link rel="stylesheet" href="./addEngine.css">
    </head>
    <body>
        <?= $message ?>
        <h1>Lok hinzufügen</h1>
            <div class="addForm">
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <table>
                <tr>
                    <th>Bild</th>
                    <th>Ordnungsnummer</th>
                    <th>Name</th>
                    <th>Besitzer</th>
                </tr>
                <tr>
                    <td>
                        <input accept="image/*" type="file" id="fileInput" name="image" onchange="loadFile(event)">
                        <img id="engine"/>
                    </td>
                    <td><input type="text" id="class" name="class" placeholder="999 999"></td>
                    <td><input type="text" id="name" name="name" placeholder="Name der Lok"></td>
                    <td><input type="text" id="owner" name="owner" placeholder="Besitzer"></td>
                </tr>
            </table>
            <button class="button-3" role="button">Bild hochladen</button> 
            </form>
            </div>

        <script>
            function loadFile(event) {
                var output = document.getElementById("engine");
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src); // free memory
                };
            }
        </script>
    </body>
</html>
