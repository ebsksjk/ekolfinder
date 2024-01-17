<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $znr = intval($_POST['number']);

    try {
        require "../../DBConnect.php";

        global $conn;
        $sql = "INSERT INTO
            trains (Typ, Trasse, Nummer, Von, Nach, Owner)
            VALUES (?,?,?,?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['type'], $_POST['trasse'], $znr, $_POST['from'], $_POST['to'], $_POST['operator']]);
    } catch (Exception $e) {
        die("Fehler beim Hochladen der Daten! " . $e->getMessage());
    }
}
?>

<h1>zug hinzufügeln</h1>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <table>
        <thead>
            <td>Zugtyp</td>
            <td>Trasse</td>
            <td>Zugnummer</td>
            <td>Von</td>
            <td>Nach</td>
            <td>Betreiber</td>
        </thead>
        <tr>
            <td><input type="text" name="type" placeholder="KLV"></td>    
            <td><input type="text" name="trasse" placeholder="DGS"></td>
            <td><input type="text" name="number" placeholder="999999"></td>
            <td><input type="text" name="from" placeholder="MOR/München Ost"></td>
             <td><input type="text" name="to" placeholder="KKE/Köln Eifeltor"></td> 
            <td><input type="text" name="operator" placeholder="Lokomotion"></td>
        </tr>
    </table>
    <button>Zug speichern</button>
</form>