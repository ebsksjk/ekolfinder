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

    $joinedCompany = date('Y-m-d', strtotime($_POST['joined']));
    $leftCompany = date('Y-m-d', strtotime($_POST['left']));
    $LiverySince = date('Y-m-d', strtotime($_POST['since']));
    $LiveryUntil = date('Y-m-d', strtotime($_POST['until']));

    try {
        $sql = "UPDATE Engines SET Baureihe=(?), Ordnungsnummer=(?), Name=(?), Owner=(?), joinedCompany=(?), leftCompany=(?), liverySince=(?), liveryUntil=(?) WHERE EngineID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$br, $nr, trim($_POST['name']), trim($_POST['owner']), $joinedCompany, $leftCompany, $LiverySince, $LiveryUntil, $_GET['ID']]);
    } catch (Exception $e) {
        die("Fehler beim Hochladen der Daten! " . $e->getMessage());
    }

    if ($_FILES['image']['error'] === 0) {

        $uploadFolder = "../data/images/";
        $filename = uniqid();

        $uploadPath = $uploadFolder . $filename . '.png';

        try {
            imagepng(imagecreatefromstring(file_get_contents($_FILES['image']['tmp_name'])), $uploadPath);
        } catch (Exception $e) {
            die("Fehler beim Hochladen des Bildes! " . $e->getMessage());
        }

        foreach ($conn->query("SELECT imagePath WHERE EngineID='" . $_GET['ID'] . "';") as $row) {
            unlink($uploadFolder . $row["imagePath"] . '.png');
        }

        $sql = "UPDATE Engines SET imagePath=(?) WHERE EngineID=(?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$filename, $_GET['ID']]);

        $message = "<div class='successBox'><p>Erfolgreich hochgeladen!</p></div>";

    } else {
        $message = "Fehler beim Upload";
    }
}

$filename = '';

foreach($conn->query("SELECT imagePath from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
    $filename = $row['imagePath'];
}
?>


<DOCTYPE! html>
<html>
    <head>
        <title>Loks bearbeiten</title>
        <link rel="stylesheet" href="addEngine.css">
        <link rel="stylesheet" href="../styles/main.css">
        <!--<link rel="stylesheet" href="../styles/table.css">-->
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
                            <th>Bei Besitzer seit</th>
                            <th>Bei Besitzer bis</th>
                            <th>Lackierung seit</th>
                            <th>Lackierung bis</th>
                        </tr>
                        <tr>
                            <td>
                                <input accept="image/*" type="file" id="fileInput" name="image" onchange="loadFile(event)">
                                <img id="engine" src='<?php
echo "../data/images/" .
    $filename .
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
                                ?>'>
                            </td>
                            <td><input type="date" id="joinedCompany" name="joined" value='<?php
                                foreach($conn->query("SELECT joinedCompany from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                    echo $row['joinedCompany'];
                                }
                            ?>'></td>
                            <td><input type="date" id="leftCompany" name="left" value='<?php
                                foreach($conn->query("SELECT leftCompany from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                    echo $row['leftCompany'];
                                }
                            ?>'></td>
                            <td><input type="date" id="liverySince" name="since" value='<?php
                                foreach($conn->query("SELECT liverySince from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                    echo $row['liverySince'];
                                }
                            ?>'></td>
                            <td><input type="date" id="liveryUntil" name="until" value='<?php
                                foreach($conn->query("SELECT liveryUntil from Engines WHERE EngineID='" . $_GET['ID'] ."';") as $row){
                                    echo $row['liveryUntil'];
                                }
                            ?>'></td>
                        </tr>
                    </table>
                    <button class="button-3" role="button">Bild hochladen</button>
                </form>
            </div>
            <div class="other-engines">
                <table class="engine-table">
                        <?php
                            $actBR = "00";
                            foreach($conn->query("SELECT Baureihe FROM Engines ORDER BY Baureihe;") as $row){
                                if($row['Baureihe']!== $actBR){
                                    $actBR = $row['Baureihe'];
                                    echo "</details></tr>";
                                    echo "<tr><details><summary>" . $row['Baureihe'] . "</summary>";
                                    foreach($conn->query("SELECT EngineID, Baureihe, Ordnungsnummer FROM Engines WHERE Baureihe='" . $actBR . "' ORDER BY Baureihe, Ordnungsnummer ;") as $row){
                                        $baureihe = $row['Baureihe'] . " " . $row['Ordnungsnummer'];
                                        echo "<a href='" . $_SERVER["PHP_SELF"] . "?ID=" . $row['EngineID'] . "'>" . $baureihe .  "</a>";
                                        echo "<br/>";
                                    }
                                }
                            }
                        ?>
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