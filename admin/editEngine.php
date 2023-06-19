<?php
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header('Location: loginUser.php');
    exit;
}

require "../DBConnect.php";

$message = '';
$baureihe = '';

foreach ($conn->query("SELECT Baureihe, Ordnungsnummer from Engines WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
    $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$message = var_dump($_POST);

    $exploder = explode(' ', trim($_POST['class']));

    $br = $exploder[0];
    $nr = $exploder[1];

    $owner = $_POST['owner'];
    $name = $_POST['name'];
    $old_class = $baureihe;

    try {
        $sql = "UPDATE Engines SET Baureihe=(?), Ordnungsnummer=(?), Name=(?), Owner=(?) WHERE EngineID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$br, $nr, trim($_POST['name']), trim($_POST['owner']), $_GET['ID']]);
    } catch (Exception $e) {
        die("Fehler beim Hochladen der Daten! " . $e->getMessage());
    }

    if ($_POST['class'] != $old_class) {
        rename("../data/images/{$old_class}.png", "../data/images/{$_POST['class']}.png");
    }

    if ($_FILES['image']['error'] === 0) {

        $uploadFolder = "../data/images/";

        $uploadPath = $uploadFolder . trim($_POST['class']) . '.png';

        try {
            imagepng(imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name'])), $uploadPath);
        } catch (Exception $e) {
            die("Fehler beim Hochladen des Bildes! " . $e->getMessage());
        }

        $message = "<div class='successBox'><p>Erfolgreich hochgeladen!</p></div>";

    } else {
        $message = "Fehler beim Upload";
    }
}

foreach ($conn->query("SELECT Baureihe, Ordnungsnummer from Engines WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
    $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
}
?>


<DOCTYPE! html>
<html>
    <head>
        <title>Loks bearbeiten</title>
        <link rel="stylesheet" href="addEngine.css">
        <link rel="stylesheet" href="../styles/main.css">
        <link rel="stylesheet" href="../styles/table.css">
        <link rel="stylesheet" href="../styles/engines.css">
    </head>
    <body>
        <?=$message?>
        <div class="flexy">
            <div>
                <form method="post" action="<?=$_SERVER['PHP_SELF'] . '?ID=' . $_GET['ID']?>" enctype="multipart/form-data">
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
                                <img id="engine" src='<?php
echo "../data/images/" .
    $baureihe .
    ".png";
?>'/>
                            </td>
                            <td><input type="text" id="class" name="class" value='<?=$baureihe?>'></td>
                            <td><input type="text" id="name" name="name" value='<?php
foreach ($conn->query("SELECT name FROM Engines WHERE EngineID=" . $_GET['ID'] . ";") as $row) {
    echo $row['name'];
}
?>'></td>
                            <td><input type="text" id="owner" name="owner" value='<?php
foreach ($conn->query("SELECT owner FROM Engines WHERE EngineID=" . $_GET['ID'] . ";") as $row) {
    echo $row['owner'];
}
?>'></td>
                        </tr>
                    </table>
                    <button class="button-3" role="button">Bild hochladen</button>
                </form>
            </div>
            <div class="other-engines">
                <table class="engine-table">
                    <tr>
                        <?php
foreach ($conn->query("SELECT EngineID, Baureihe, Ordnungsnummer FROM Engines ORDER BY Baureihe, Ordnungsnummer;") as $row) {
    echo "<tr><td><a href='" . $_SERVER["PHP_SELF"] . "?ID=" . $row['EngineID'] . "'>" . $row['Baureihe'] . " " . $row['Ordnungsnummer'] . "</a></td></tr>";
}
?>
                    </tr>
                </table>
            </div>
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