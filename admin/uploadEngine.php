<?php

ob_start(); // Pufferung starten

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