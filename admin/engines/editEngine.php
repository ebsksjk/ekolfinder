<?php
session_start();

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header('Location: ../loginUser.php');
    exit;
}

require "../../DBConnect.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
        $engineID = intval($_GET['ID']);
    
        if (isset($_POST['delete'])) {
            try {
                $sql = "DELETE FROM engines WHERE EngineID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$engineID]);
    
                $message = "<div class='successBox'><p>Erfolgreich gelöscht!</p></div>";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } catch (Exception $e) {
                die("Fehler beim Löschen der Daten! " . $e->getMessage());
            }
        }
    }

    $exploder = explode(' ', trim($_POST['class']));

    $br = $exploder[0];
    $nr = $exploder[1];

    $owner = $_POST['owner'];
    $name = $_POST['name'];
    $old_class = $baureihe;

    $joinedCompany = date('Y-m-d', strtotime($_POST['joined']));
    $leftCompany = date('Y-m-d', strtotime($_POST['left']));
    $LiverySince = date('Y-m-d', strtotime($_POST['since']));
    $LiveryUntil = date('Y-m-d', strtotime($_POST['until']));

    try {
        $sql = "UPDATE engines SET Baureihe=(?), Ordnungsnummer=(?), Name=(?), Owner=(?), joinedCompany=(?), leftCompany=(?), liverySince=(?), liveryUntil=(?) WHERE EngineID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$br, $nr, trim($_POST['name']), trim($_POST['owner']), $joinedCompany, $leftCompany, $LiverySince, $LiveryUntil, $_GET['ID']]);
    } catch (Exception $e) {
        die("Fehler beim Hochladen der Daten! " . $e->getMessage());
    }

    if ($_FILES['image']['error'] === 0) {

        $uploadFolder = "../../data/images/";
        $filename = uniqid();

        $uploadPath = $uploadFolder . $filename . '.png';

        try {
            imagepng(imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name'])), $uploadPath);
        } catch (Exception $e) {
            die("Fehler beim Hochladen des Bildes! " . $e->getMessage());
        }

        foreach ($conn->query("SELECT imagePath FROM engines WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
            unlink($uploadFolder . $row["imagePath"] . '.png');
        }

        $sql = "UPDATE engines SET imagePath=(?) WHERE EngineID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$filename, $_GET['ID']]);

        $message = "<div class='successBox'><p>Erfolgreich hochgeladen!</p></div>";

    } else {
        $message = "Fehler beim Upload";
    }
}
if (isset($_GET['ID'])) {
    $renderTrain = true;
    $filename = $baureihe = $Name = $owner = $joinedCompany = $leftCompany = $liverySince = $liveryUntil = '';

    foreach ($conn->query("SELECT * from engines WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
        $filename = $row['imagePath'];
        $baureihe = $row["Baureihe"] . ' ' . $row['Ordnungsnummer'];
        $Name = $row['Name'];
        $owner = $row['Owner'];
        $joinedCompany = $row['joinedCompany'];
        $leftCompany = $row['leftCompany'];
        $liverySince = $row['liverySince'];
        $liveryUntil = $row['liveryUntil'];
    }
} else {
    $renderTrain = false;
}
?>
<DOCTYPE! html>
<html>
    <head>
        <title>Loks bearbeiten</title>
        <link rel="stylesheet" href="addEngine.css">
        <link rel="stylesheet" href="../../styles/main.css">
        <!--<link rel="stylesheet" href="../styles/table.css">-->
        <link rel="stylesheet" href="../../styles/engines.css">
    </head>
    <body>
        <?=$message?>
        <div class="flexy">
            <div>
                <?php if($renderTrain): ?>
                <form method="post" action="<?=$_SERVER['PHP_SELF'] . '?ID=' . $_GET['ID']?>" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th>Bild</th>
                            <th>Ordnungsnummer</th>
                            <th>Name</th>
                            <th>Besitzer</th>
                            <th>Bei Besitzer seit</th>
                            <th>Bei Besitzer bis</th>
                            <th>Lackierung seit</th>
                            <th>Lackierung bis</th>
                        </tr>
                        <tr>
                            <td>
                                <input accept="image/*" type="file" id="fileInput" name="image" onchange="loadFile(event)">
                                <img id="engine" src='../../data/images/<?=$filename?>.png' />
                            </td>
                            <td><input type="text" id="class" name="class" value='<?=$baureihe?>'></td>
                            <td><input type="text" id="name" name="name" value='<?=$Name?>'></td>
                            <td><input type="text" id="owner" name="owner" value='<?=$owner?>'></td>
                            <td><input type="date" id="joinedCompany" name="joined" value='<?=$joinedCompany?>'></td>
                            <td><input type="date" id="leftCompany" name="left" value='<?=$leftCompany?>'></td>
                            <td><input type="date" id="liverySince" name="since" value='<?=$liverySince?>'></td>
                            <td><input type="date" id="liveryUntil" name="until" value='<?=$liveryUntil?>'></td>
                        </tr>
                    </table>
                    <button class="button-3" role="button">Lok speichern</button>
                    <button class="button-3" name="delete" role="button" onclick="return confirm('Lok löschen?')">Lok löschen</button>
                </form>
                <?php else: ?>
                <h1>Loks bearbeiten</h1>
                <p>Bitte eine Lok auswählen</p>
                <?php endif; ?>
            </div>
            <?php
                include "../../enginetable.php";
            ?>
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