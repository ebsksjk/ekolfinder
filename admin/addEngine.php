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

require("../DBConnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baureihe = "999 999";
    $name = "";
    $owner = "";

    if(isset($_POST['baureihe'])){
        $baureihe = $_POST['baureihe'];
    }

    if(isset($_POST['name'])){
        $name = $_POST['name'];
    }

    if(isset($_POST['owner'])){
        $owner = $_POST['owner'];
    }

    if (isset($_FILES['image'])) {
        $targetDir = '../data/images/';
        $ext = pathinfo(basename($_FILES['image']['name']), PATHINFO_EXTENSION);
        $targetFile = $targetDir . $baureihe . '.' . $ext;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            /*$sql = "INSERT INTO Engines (Baureihe, Name, Owner)
            VALUES (". $baureihe . ", " . $name . ", " . $owner . ");";
            $surpress = $DBASE->query();
            */
            $sql = "INSERT INTO Engines (Baureihe, Name, Owner) VALUES (?,?,?);";
            $stmt= $DBASE->prepare($sql);
            $stmt->execute([$baureihe, $name, $owner]);    

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

    //ob_end_clean(); // Puffer leeren
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Beende das Skript hier, um das Frontend nicht auszuführen
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lok hinzufügen</title>
    <link rel="stylesheet" href="../styles/addEngine.css">
</head>
<body>
    <h1>Lok hinzufügen</h1>
    <div>
        <form method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Bild</th>
                <th>Baureihe</th>
                <th>Name</th>
                <th>Besitzer</th>
            </tr>
            <tr>
                <td>
                    <input accept="image/*" type="file" id="fileInput" name="image" onchange="loadFile(event)">
                    <img id="engine"/>
                </td>
                <td><input type="text" id="class"></td>
                <td><input type="text" id="name"></td>
                <td><input type="text" id="owner"></td>
            </tr>

        </table>
        <button class="button-3" role="button" onclick="uploadImage(event)" >Bild hochladen</button> 
        </form>
    </div>

    <script>
       function loadFile(event) {
            var output = document.getElementById('engine');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            };
        }

        function uploadImage(event) {
            event.preventDefault(); // Standardverhalten des Formulars unterdrücken

            var fileInput = document.getElementById('fileInput');
            var baureihe = document.getElementById('class');
            var name = document.getElementById('name');
            var owner = document.getElementById('owner');

            var file = fileInput.files[0];
            var formData = new FormData();

            console.log(file.name);
            console.log(baureihe.value);
            console.log(owner.value);

            formData.append('image', file, file.name);
            formData.append('baureihe', baureihe.value);
            formData.append('name', name.value);
            formData.append('owner', owner.value);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log('Bild erfolgreich hochgeladen.');
                        fileInput.value = null;
                        baureihe.value = null;
                        owner.value = null;
                        name.value = null;

                        const img = document.getElementById('engine');
                        img.setAttribute('src', '');

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
